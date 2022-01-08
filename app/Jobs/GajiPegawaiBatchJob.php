<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Pegawai;
use App\Models\GajiPegawai;
use DB;
use Validator;

class GajiPegawaiBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id_pegawai;
    protected $total_diterima;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id_pegawai, $total_diterima)
    {
        $this->id_pegawai = $id_pegawai;
        $this->total_diterima = $total_diterima;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $id_pegawai = $this->id_pegawai;
        $total_diterima = $this->total_diterima;

        $gaji_pegawai = GajiPegawai::create([
            'id_pegawai' => $id_pegawai,
            'total_diterima' => $total_diterima,
            'waktu' => date('Y-m-d H:i')
        ]);

    }
}
