<?php

namespace App\Http\Controllers;

use DB;
use File;
use Illuminate\Http\Request;
use App\Models\Files;


class FileController extends Controller
{
    public function loadberkas(Request $request)
    {
        $id = $request->ID;

        $classname = $request->ClassName;
        $classname = 'App\\Models\\'.$classname;

        $FileField = $request->FileField;
        $data = $classname::find($id);
        // dd($data->Files()->get());
        if ($data) {
            return self::getPreviewFileKrajee($data->$FileField()->get(), $data, $FileField, null, null, null, $request->ClassName);
        } else {
            return self::getPreviewFileKrajee(array(), null, $FileField);
        }
    }

    function uploadfile(Request $request)
    {
        $classname = $request->ClassName;
        $data_id = $request->DataID;
        $FileField = $request->FileField;
        if (!$classname || !$FileField) {
            return false;
        }
        $classname = 'App\\Models\\'.$classname;
        $data = $classname::find($data_id);


        if (!$request->$FileField) {
            return false;
        }



        $initialPreview = array();
        $initialPreviewConfig = array();
        $files = array();
        $files_id = array();
        $filenya = $_FILES[$FileField];

        // for ($i = 0; $i < count($filenya['size']); $i++) {
        foreach ($request->$FileField as $row_temp) {
            $files_temp = $row_temp;
            // menyimpan data file yang diupload ke variabel $file
            $file_arr = $row_temp;

            // // nama file
            $FileName = $file_arr->getClientOriginalName();

            // // ekstensi file
            $FileExtension = $file_arr->getClientOriginalExtension();

            // // real path
            $FileRealPath = $file_arr->getRealPath();

            $FileSize = $file_arr->getSize();

            // tipe mime
            // $FileMimeType = $file_arr->getMimeType();

                // isi dengan nama folder tempat kemana file diupload
            $tujuan_upload = 'Assets';
            $name_now = date('YmdHis').$FileName;
            $file_arr->move($tujuan_upload,$name_now);

            $new_file = new Files;
            $new_file->src=$tujuan_upload.'/'.$name_now;
            $new_file->title=$name_now;
            $new_file->save();
            // dd('cokk');

            $files_id[] = $new_file->id;
            $row = $new_file;
            $url = public_path($row->src);
            $url_preview = $row->src;

            $ext = pathinfo($url, PATHINFO_EXTENSION);

            if (@is_array(getimagesize($url))) {
                $initialPreview[] = $url_preview;
            } elseif ($ext) {
                if ($ext == 'xls' || $ext == 'csv' || $ext == 'doc' || $ext == 'xlsx') {
                    $ext = 'office';
                }
                $initialPreview[] = "$url_preview";
                $tmp_config['type'] = $ext;
            } else {
                $initialPreview[] = "<img class=file-preview-image kv-preview-data src=$file_o>";
            }






            $as_data = false;
            $delete_url = 'file/deletefile';

            if ($data) {
                $delete_url .= '?DataID=' . $data->id . '&ID=' . $row->id. '&DataField=' . $FileField . '&ClassName=' . $classname . '&FileField=' . $FileField;

                $data->$FileField()->attach($new_file->id);
            }

            $tmp_config['caption'] = $row->title;
            $tmp_config['size'] = $FileSize;
            $tmp_config['downloadUrl'] = $url_preview;
            $tmp_config['url'] = $delete_url;
            $tmp_config['key'] = $row->id;
            $as_data = true;
            $initialPreviewConfig[] = $tmp_config;
        }



        return json_encode(array(
            'initialPreview' => $initialPreview,
            'initialPreviewAsData' => $as_data,
            'initialPreviewConfig' => $initialPreviewConfig,
            'ID' => $files_id,
        ));
    }

    function deletefile(Request $request)
    {
        $id = $request->ID;

        $file_data = Files::find($id);
        // var_dump($file_data->getFullPath());die();
        $classname = $request->ClassName;
        $classname = 'App\\Models\\'.$classname;
        $data_id = $request->DataID;
        $FileField = $request->FileField;

        if ($file_data) {
            $file_data->delete();
        }

        return true;
    }

    static function getPreviewFileKrajee($data_file, $data = null, $datafield = '',$parent_url = '', $delete_url = '', $use_external_url = false, $classname = '')
    {
        $array = array();
        $arr_initialPreview = array();
        $arr_initialPreviewConfig = array();
        $str_initialPreview = '';
        $str_initialPreviewConfig = '';
        $str = '';
        $data_id = array();
        $global_delete_url = $delete_url;
        foreach ($data_file as $row) {

            $url = public_path($row->src);
            $url_preview = $row->src;

            $file_o = null;
            $size_file = 0;
            // dd(public_path($url));
            // $size_file = Storage::size($row->title);
            $size_file = File::size(public_path($row->src));

            // dd($size_file);
            // //            echo '<pre>';var_dump($size_file);die();
            $size_file = $size_file ? $size_file : 0;
            $ext = pathinfo($url, PATHINFO_EXTENSION);
                    //    var_dump($ext);die();

            $config_type = "";
            $tmp_config = array();
            if (@is_array(getimagesize($url))) {
                $str_initialPreview .= "'$url_preview',";
                $arr_initialPreview[] = "$url_preview";
            } elseif ($ext) {
                if ($ext == 'xls' || $ext == 'csv' || $ext == 'doc' || $ext == 'xlsx') {
                    $ext = 'office';
                }
                $str_initialPreview .= "'$url_preview',";
                $arr_initialPreview[] = "$url_preview";
                $config_type = '"type": ' . $ext . ',';
                $tmp_config['type'] = $ext;
            } else {
                $str_initialPreview .= "'<img class=file-preview-image kv-preview-data src=$file_o>',";
                $arr_initialPreview[] = "<img class=file-preview-image kv-preview-data src=$file_o>";
            }

            $delete_url = 'file/deletefile?DataID=' . $data->id . '&ID=' . $row->id . '&DataField=' . $datafield . '&ClassName=' . $classname . '&FileField=' . $datafield;

            if ($str_initialPreviewConfig) {
                $str_initialPreviewConfig .= ",";
            }

            $tmp_config['caption'] = $row->title;
            $tmp_config['size'] = $size_file;
            $tmp_config['downloadUrl'] = $url;
            $tmp_config['url'] = $delete_url;
            $tmp_config['key'] = $row->id;
            $data_id[] = $row->id;
            $config_json = '{' . $config_type . '"caption": "' . $row->Name . '","size": "' . $size_file . '","downloadUrl":"' . $url . '", "url": "' . $delete_url . '", "key": "' . $row->ID . '"}';
            //                echo $config_json;die();
            $arr_initialPreviewConfig[] = $tmp_config;
        }
        $str .= "initialPreview: [$str_initialPreview], initialPreviewConfig: [$str_initialPreviewConfig]";
        //        echo '<pre>'.$str;die();
        $res =  array(
            'initialPreview' => $arr_initialPreview,
            'initialPreviewConfig' => $arr_initialPreviewConfig,
            'DataID' => $data_id
        );

        return json_encode($res);
    }
}
