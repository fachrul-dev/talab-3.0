<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusRequest extends Model
{
    use HasFactory;
    protected $table = 'status_request';

    public function Request()
    {
        return $this->hasOne(Request::class, 'id','request_id');
    }

}
