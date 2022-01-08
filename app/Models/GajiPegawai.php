<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiPegawai extends Model
{
    use HasFactory;

    protected $fillable = ['id_pegawai', 'total_diterima', 'waktu'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'waktu'
    ];

    public function getTotalDiterimaAttribute($value)
    {
        $total_diterima = number_format($value,2,',','.');
        return $total_diterima;
    }

    public function pegawai()
    {
        return $this->hasOne('App\Models\Pegawai', 'id', 'id_pegawai');
    }
    


}
