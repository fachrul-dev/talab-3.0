<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Strukturs;

use App\Http\Controllers\StrukturController;

class RequestData extends Model
{
    use HasFactory;

    protected $table = 'request_data';
    protected $fillable = [
        'id',
        'title',
        'requirements',
        'type',
        'user_id',
    ];
    /**
     * Get the User associated with the RequestData
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function User()
    {
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function Files(){
        return $this->belongsToMany(Files::class, 'request_files', 'request_id');
    }

    public static function getButtonEmail($request_id, $struktur, $email = ''){
        $request_data = RequestData::find($request_id);
        $link = '/request/updatestatusrequest/'.$request_data->id .'?struktur='.$struktur.'&email='.$email.'&';
        $approve = $link."status=approved";
        $reject = $link."status=rejected";

        return [
            'Approve' => $approve,
            'Reject' => $reject
        ];
    }

    public static function getAllStatusRequest($request_id){
        $find = StatusRequest::where('request_id','=', $request_id)->orderBy('id', 'asc')->get();

        return $find;
    }
    public static function checkIfGenerateStatus($request_id){
        $find = StatusRequest::where('request_id','=', $request_id)->first();

        if($find){
            return true;
        }

        return false;
    }

    public function getLastStatus(){
        $status = self::getAllStatusRequest($this->id);
        $obj_last = null;
        foreach($status as $row){
            $status = $row->status;
            $struktur = StrukturController::getStrukturLabel($row->struktur);
            if($status == 'rejected'){
                return $row;
            }elseif($status == 'processed'){
                // $last_status = 'Masih menunggu Konfirmasi '.$struktur;
                $obj_last = $row;
            }elseif($status == 'approved'){
                // $last_status = 'Data Berhasil di konfirmasi oleh semua bagian';
                $obj_last = $row;
            }
        }

        if($obj_last){
            return $obj_last;
        }
    }
}
