<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Strukturs;
use DataTables;
use DB;
use Auth;

class StrukturController extends Controller
{

    static $struktur_arr = [
        'dept_head' => 'Department Head',
        'hr' => 'HR',
        'hr_director' => 'HR Director',
        'finance_director' => 'Finance Director',
        'ceo' => 'CEO'
    ];

    public static function getAllStruktur(){
        return self::$struktur_arr;
    }
    public static function getStrukturLabel($key){
        if(isset(self::$struktur_arr[$key])){
            return self::$struktur_arr[$key];
        }
    }

    public static function getUrutanEmailRequest($type_request = ''){
        $set = self::$struktur_arr;
        if($type_request == 'fte' || $type_request == 'fte_director'){
            return array_keys($set);
        }elseif($type_request == 'nonfte' || $type_request = 'nonfte_contract'){
            unset($set['ceo']);

            return array_keys($set);

            return $set;
        }

        return false;
    }

    public function index(Request $request){
        if ($request->ajax()) {

            $data = Strukturs::latest()->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_at', function($row){
                        return date('d/m/Y H:i', strtotime($row->created_at));
                    })
                    ->editColumn('strukturs', function($row){
                        $struktur_arr = self::$struktur_arr;
                        if(isset($struktur_arr[$row->struktur])){
                            return $struktur_arr[$row->struktur];
                        }
                        return null;
                    })
                    ->addColumn('action', function($row){

                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';

                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('struktur');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $user = Auth::user();
        $id_arr = ['id' => $request->id];
        $input_arr = [
            'struktur' => $request->struktur,
            'name' => $request->name,
            'email' => $request->email,
        ];

        $data = Strukturs::updateOrCreate($id_arr,$input_arr);


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
        $product = Strukturs::find($id);
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
        Strukturs::find($id)->delete();

        return response()->json(['success'=>'Record deleted successfully.']);
    }
}
