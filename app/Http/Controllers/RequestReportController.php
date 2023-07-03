<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\RequestData;

class RequestReportController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $data_report = RequestData::where("user_id", "=", $user->id)->orderBy("created_at", 'DESC')->get();
        // dd($data_report);
        return view('request-report.index', compact('data_report'));
    }
}
