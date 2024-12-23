<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class MedicalrecordPemeriksaanEegModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'pemeriksaan_eeg';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.pemeriksaan_eeg as pemeriksaanEeg')
            ->select([
                'pemeriksaanEeg.*',
                DB::raw('CONCAT(dokterEeg.GELAR_DEPAN, " ", dokterEeg.NAMA, " ", dokterEeg.GELAR_BELAKANG) as DOKTER'),
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH')
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'pemeriksaanEeg.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'pemeriksaanEeg.DOKTER')
            ->leftJoin('master.pegawai as dokterEeg', 'dokterEeg.NIP', '=', 'dokter.NIP')
            ->where('pemeriksaanEeg.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}