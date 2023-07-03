<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Strukturs;
use App\Http\Controllers\StrukturController;
class StatusRequest extends Model
{
    use HasFactory;
    protected $table = 'status_request';

    public function Request()
    {
        return $this->hasOne(Request::class, 'id','request_id');
    }

    public function getAllStruktur(){
        $data = Strukturs::where("struktur", '=', $this->struktur)->get();

        return $data;
    }

    public function getAllEmailConcatStruktur(){
        $str = '';
        if($this->getAllStruktur()){
            foreach($this->getAllStruktur() as $row){
                if($str){
                    $str .= ', ';
                }

                $str .= $row->email;
            }
        }

        return $str;
    }

    public function getStrukturLabel(){
        if($this->struktur && isset(StrukturController::$struktur_arr[$this->struktur])){
            return StrukturController::$struktur_arr[$this->struktur];
        }
    }

}
