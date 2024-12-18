<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordKeluhanUtamaModel extends Model
{
    use HasFactory;

    // Koneksi ke database yang digunakan
    public $connection = "mysql11";

    // Menentukan tabel yang digunakan
    protected $table = 'keluhan_utama';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql5')->table('medicalrecord.keluhan_utama as keluhanUtama')
            ->select([
                'keluhanUtama.ID as ID',
                'keluhanUtama.KUNJUNGAN as KUNJUNGAN',
                'keluhanUtama.DESKRIPSI as DESKRIPSI',
                'keluhanUtama.SNOMED_CT_ID as SNOMED',
                'keluhanUtama.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'keluhanUtama.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'keluhanUtama.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('keluhanUtama.ID', $id)
            ->distinct()
            ->first();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}