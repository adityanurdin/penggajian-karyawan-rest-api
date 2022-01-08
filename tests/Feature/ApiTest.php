<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{

    public function test_create_pegawai()
    {
        for ($i=1; $i < 10; $i++) { 
            $response = $this->postJson('/api/pegawai', [
                'nama_pegawai'  => "Aditya ".rand(123, 456),
                'total_gaji'    => 4000000
            ]);
        }
        
        $response->assertStatus(200);
    }

    public function test_get_pegawai()
    {
        $response = $this->get('/api/pegawai');

        $response->assertStatus(200);
    }

    public function test_create_gaji_pegawai()
    {
        $response = $this->postJson('/api/gaji-pegawai', [
            'id_pegawai'    => 1,
            'total_diterima'    => 4000000
        ]);

        $response->assertStatus(200);
    }

    public function test_get_gaji_pegawai()
    {
        $response = $this->get('/api/gaji-pegawai');

        $response->assertStatus(200);
    }
    
    public function test_create_gaji_pegawai_batch()
    {
        $data = [];
        for ($i=2; $i < 10; $i++) { 
            array_push($data,[
                'id_pegawai'        => $i,
                'total_diterima'    => 4000000
            ]);
        }
        $response = $this->postJson('/api/gaji-pegawai/batch', $data);

        $response->assertStatus(200);
    }
}
