<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Models\Pegawai;
use DB;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # get all data pegawais
        $pegawai = Pegawai::paginate();

        return response()->json([
            'status' => true,
            'msg' => 'successfully retrieve data pegawai',
            'data' => $pegawai,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        # initial rules
        $rule = [
            'nama_pegawai' => 'required|string|max:10|unique:pegawais,nama_pegawai',
            'total_gaji' => 'required|integer|min:4000000|max:10000000'
        ];

        #validation
        $validation = Validator::make($request->all(), $rule);

        #check validation
        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'msg'   => 'Error validation',
                'error' => $validation->errors()
            ], 400);
        }

        try {

            # Insert data to table pegawais
            DB::Transaction(function() use ($request, &$pegawai) {
                $pegawai = Pegawai::create($request->all());
            });

            return response()->json([
                'status' => true,
                'msg' => 'success create data pegawai',
                'data' => [
                    'nama_pegawai' => $pegawai->nama_pegawai,
                    'total_gaji'   => $pegawai->total_gaji
                ]
            ],200);


        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'msg'   => 'Error while create data pegawai',
                'error' => $th->getMessage()
            ], 500);

        }
    }
}
