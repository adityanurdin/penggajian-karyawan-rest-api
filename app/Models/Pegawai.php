<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = ['nama_pegawai', 'total_gaji'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    # get nama_pegawai first word & uppercase
    public function getNamaPegawaiAttribute($value)
    {
        $first_word = explode(' ', $value);
        return strtoupper($first_word[0]);
    }

    public function getTotalGajiAttribute($value)
    {
        $total_gaji = number_format($value,2,',','.');
        return $total_gaji;
    }

    // public function gaji()
    // {
    //     return $this->belongsTo()
    // }
}
