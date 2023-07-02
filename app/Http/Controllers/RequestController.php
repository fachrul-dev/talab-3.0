<?php

namespace App\Http\Controllers;

use DB;
// use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Models\RequestData;
use App\Models\StatusRequest;
use App\Models\Strukturs;
use App\Models\User;
use App\Http\Controllers\StrukturController;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use DataTables;

class RequestController extends Controller
{
    function loadviewstatusrequest(Request $request){
        
        $request_id = $request->id;
        $list = RequestData::getAllStatusRequest($request_id);
        $request_data = RequestData::find($request_id);
        $bulet ='';
        $content = '';
        $last_status = '';
        $last_status_reject = '';
        foreach($list as $row){
            $status = $row->status;
            if($row->status == 'approved'){
                $bulet .= '<div class="roundChecked"></div>
                <div class="lineChecked"></div>';
            }else{
                $bulet .= '<div class="roundUnChecked"></div>
                <div class="lineUnChecked"></div>';
            }


            $struktur = StrukturController::getStrukturLabel($row->struktur);
            if($status == 'rejected'){
                if(!$last_status_reject){
                    $last_status_reject = 'Telah di Reject oleh Bagian '.$struktur;
                }
            }elseif($status == 'processed'){
                $last_status = 'Masih menunggu Konfirmasi '.$struktur;
            }elseif($status == 'approved'){
                $last_status = 'Data Berhasil di konfirmasi oleh semua bagian';
            }
            $detail_content = '';
            if($status == 'approved' || $status == 'rejected'){
                $detail_content = '<span class="desc-line">'.$row->ChangedByEmail.'</span>
                <br>
                <span class="desc-line">'.date('d/m/Y H:i:s', strtotime($row->ChangedAt)).'</span>';
            }
            $content .= '<div class="inner-panel">
                            <div class="col-xs-1">

                            </div>
                            <div class="col-xs-3 center">
                                <span class="desc-bold">'.$struktur.'</span>
                                <span class="desc-line">'.$row->status.'</span>
                            </div>
                            <div class="col-xs-2 center">
                            '.$detail_content.'

                            </div>
                            <div class="col-xs-1 center">
                                <span class="glyphicon glyphicon-ok sign"></span>
                            </div>
                            <div class="col-xs-2 center">

                            </div>
                            <div class="col-xs-3 center">

                            </div>

                        </div><br />';
        }
        $last_status = $last_status_reject?$last_status_reject:$last_status;
        $arr =  [
            'Bullet' => $bulet,
            'Content' => $content,
            'LastStatus' => $last_status
        ];

        return response()->json($arr);
    }
    public function index(Request $request)
{

    
    if ($request->ajax()) {
        $user = Auth::user(); // Get the currently logged-in user

        // Fetch data only for the logged-in user
        $data = RequestData::where('user_id', $user->id)->latest()->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                $created_at = \Carbon\Carbon::parse($row->created_at, 'UTC')->setTimezone('Asia/Jakarta');
                return $created_at->format('d/m/Y H:i');
            })
            ->editColumn('type', function ($row) {
                $type = str_replace('_', ' ', $row->type);
                $type = strtoupper($type);
                return $type;
            })
            ->addColumn('User', function ($row) {
                return $row->User->name;
            })
            ->addColumn('action', function ($row) {
                $check = RequestData::checkIfGenerateStatus($row->id);
                if (!$check) {
                    $btn_second = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="SendEmail" class="btn btn-warning btn-sm SendEmailRequest"><i class="fas fa-envelope"></i> Send Email</a>';
                } else {
                    $btn_second = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '"  class="btn btn-info btn-sm StatusRequest"><i class="fas fa-info"></i> Check Status</a>';
                }
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';

                $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';
                $btn .= $btn_second;

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('request.index');
}



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $id_arr = ['id' => $request->id];
        $input_arr = [
            'title' => $request->title,
            'requirements' => $request->requirements,
            'type' => $request->type,
        ];
        if(!$request->id){
            $input_arr['user_id'] = $user->id;
        }
        $data = RequestData::updateOrCreate($id_arr,$input_arr);



        DB::delete("DELETE FROM request_files WHERE request_id = '$data->id'");
        $files_val = $request->input('files');
        if($files_val){
            $arr_files = json_decode($files_val, true);

            foreach($arr_files as $row){
                DB::insert("insert into request_files (request_id, files_id) values ('$data->id','$row')");
            }
        }

        return response()->json(['success'=>'Record saved successfully.']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = RequestData::find($id);
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RequestData::find($id)->delete();

        return response()->json(['success'=>'Record deleted successfully.']);
    }

    public static function sendEmailFeedback($request_id, $status){
        $request_data = RequestData::find($request_id);
        $user = User::find($request_data->user_id);
        // dd($request_data);

        // dd($button_email);
        $struktur_title = StrukturController::getStrukturLabel($status);

        $data = [
            'name' => 'Halo '.$user->name,
            'body' => 'Data berikut telah di '.strtolower(ucwords($status)),
            'DataRequest' => $request_data,
            'Attachments' => null,
        ];

        Mail::to($user->email)->send(new SendEmail($data));

    }
    public static function sendEmailRequestByDataID($status_request_id){
        $status_request = StatusRequest::find($status_request_id);
        $request_data = RequestData::find($status_request->request_id);
        // dd($request_data);

        // dd($button_email);
        $struktur_title = StrukturController::getStrukturLabel($status_request->struktur);
        $attach = [];
        foreach($request_data->Files as $row){
            $attach[] = Attachment::fromPath($row->src);
        }
        // dd($attach);

        $struktur_list = Strukturs::where('struktur', '=', $status_request->struktur)->get();
        $users = Auth::user()->name;
        foreach($struktur_list as $list){
            $button_email = RequestData::getButtonEmail($request_data->id, $status_request->struktur, $list->email);
            $data = [
                'name' => 'Halo '.$list->name.' ['.$struktur_title.']',
                'body' => 'Mohon Konfirmasi Data ini',
                'name_pengirim' => $users,
                'DataRequest' => $request_data,
                'ButtonStatus' => $button_email,
                'Attachments' => $attach,
            ];

            Mail::to($list->email)->send(new SendEmail($data));
        }

    }

    public function updatestatusrequest(Request $request){
        $id = $request->id;
        $status = $request->status;
        $struktur = $request->struktur;
        $email = $request->email;
        $get_data = StatusRequest::where('request_id', '=', $id, 'and')->where('struktur','=', $struktur)->first();
        if(!$get_data){
            return false;
        }
        $get_data->status = $status;
        $get_data->ChangedByEmail = $email;

        date_default_timezone_set('Asia/Jakarta');
        $get_data->ChangedAt = date('Y-m-d H:i:s');

        $get_data->save();
        $get_data = StatusRequest::where('request_id', '=', $id, 'and')->where('id','>', $get_data->id)->orderBy('ID', 'asc');
        if($status == 'approved'){
            $get_data = $get_data->first();
            if($get_data){
                $get_data->status = 'processed';
                $get_data->save();

                self::sendEmailRequestByDataID($get_data->id);
            }else{
                self::sendEmailFeedback($id,$status);
            }
        }elseif($status == 'rejected'){
            foreach($get_data->get() as $data){
                $data->status = 'rejected';
                $data->ChangedByEmail = $email;
                $data->ChangedAt = date('Y-m-d H:i:s');
                $data->save();
            }

            self::sendEmailFeedback($id,$status);
        }
        //bisa diganti dengan view sukses konfirmasi data request
        return 'Konfirmasi anda berhasil dikirim';
    }

    public function sendemailrequest(Request $request){

        $request_obj = RequestData::find($request->id);
        $find = StatusRequest::where('request_id','=', $request_obj->id)->first();
        if($find){

            return response()->json(['failed'=>'Data ini sudah pernah terkirim email.']);

        }

        $role_status = StrukturController::getUrutanEmailRequest($request_obj->type);
        $i = 0;
        $obj_first = null;
        foreach($role_status as $row){

            $new = new StatusRequest();
            if(!$i){
                $new->status = 'processed';
            }else{
                $new->status = 'pending';
            }

            $new->struktur = $row;
            $new->request_id = $request_obj->id;
            $new->save();
            if(!$i){
                $obj_first = $new;
            }
            $i++;
        }

        if($obj_first){
            self::sendEmailRequestByDataID($obj_first->id);
        }

        return response()->json(['success'=>'Data berhasil terkirim ke email direksi terkait']);

    }
}
