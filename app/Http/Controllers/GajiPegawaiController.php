<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Pegawai;
use App\Models\GajiPegawai;
use App\Jobs\GajiPegawaiBatchJob;

use Validator;
use DB;

class GajiPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gaji_pegawai = GajiPegawai::orderBy('waktu', 'desc')->get();
        $data = [];
        foreach ($gaji_pegawai as $item) {
            array_push($data, [
                'waktu' => date('d/m/Y H:i', strtotime($item->waktu)),
                'nama_pegawai' => $item->pegawai->nama_pegawai,
                'total_diterima' => $item->total_diterima
            ]);
        }
        return response()->json([
            'status' => true,
            'msg' => 'successfully retrieve data gaji pegawai',
            'data' => $this->paginate($data),
        ],200);
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
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
            'id_pegawai' => 'required|integer|exists:pegawais,id',
            'total_diterima' => 'required|integer'
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

            $pegawai = Pegawai::findOrFail($request->id_pegawai);
                
            if (number_format($request->total_diterima,2,',','.') != $pegawai->total_gaji) {
                return response()->json([
                    'status' => false,
                    'msg'   => 'total_diterima is not equal to total_gaji',
                ],400);
            }

            $gaji_pegawai_check = GajiPegawai::where('id_pegawai', $request->id_pegawai)
                                                ->whereMonth('created_at', date('m'))
                                                ->first();
            if ($gaji_pegawai_check) {
                return response()->json([
                    'status' => false,
                    'msg'   => 'data gaji pegawai with this id pegawai already exist at this month',
                ],400);
            }

            # Insert data to table gaji_pegawais
            DB::Transaction(function() use ($request, &$gaji_pegawai) {

                $request->merge(['waktu' => date('Y-m-d H:i')]);
                $gaji_pegawai = GajiPegawai::create($request->all());
                
            });

            return response()->json([
                'status' => true,
                'msg'   => 'success create data gaji pegawai',
                'data'  => [
                    'waktu'             => date('d/m/Y H:i', strtotime($request->waktu)),
                    'nama_pegawai'      => $gaji_pegawai->pegawai->nama_pegawai,
                    'total_diterima'    => $gaji_pegawai->total_diterima
                ]
            ],200);


        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'msg'   => 'Error while create data gaji pegawai',
                'error' => $th->getMessage()
            ], 500);

        }
    }

    public function store_batch(Request $request)
    {
        foreach ($request->all() as $item) {

            # initial rules
            $rule = [
                'id_pegawai' => 'required|integer|exists:pegawais,id',
                'total_diterima' => 'required|integer'
            ];

            #validation
            $validation = Validator::make([
                'id_pegawai'        => $item['id_pegawai'],
                'total_diterima'    => $item['total_diterima']
            ], $rule);

            #check validation
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'msg'   => 'Error validation',
                    'error' => $validation->errors()
                ], 400);
            }

            $pegawai = Pegawai::findOrFail($item['id_pegawai']);
                
            if (number_format($item['total_diterima'],2,',','.') != $pegawai->total_gaji) {
                return response()->json([
                    'status' => false,
                    'msg'   => 'total_diterima is not equal to total_gaji',
                ],400);
            }

            $gaji_pegawai_check = GajiPegawai::where('id_pegawai', $item['id_pegawai'])
                                                ->whereMonth('created_at', date('m'))
                                                ->first();
            if ($gaji_pegawai_check) {
                return response()->json([
                    'status' => false,
                    'msg'   => 'data gaji pegawai with this id pegawai already exist at this month',
                ],400);
            }

            GajiPegawaiBatchJob::dispatch($item['id_pegawai'], $item['total_diterima']);
        }

        return response()->json([
            'status' => true,
            'msg'   => 'success create data gaji pegawai',
        ],200);
    }
}
