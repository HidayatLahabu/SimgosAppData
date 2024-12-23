<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordPemeriksaanEmgModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'pemeriksaan_emg';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.pemeriksaan_emg as pemeriksaanEmg')
            ->select([
                'pemeriksaanEmg.*',
                DB::raw('CONCAT(dokterEmg.GELAR_DEPAN, " ", dokterEmg.NAMA, " ", dokterEmg.GELAR_BELAKANG) as DOKTER'),
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH')
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'pemeriksaanEmg.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'pemeriksaanEmg.DOKTER')
            ->leftJoin('master.pegawai as dokterEmg', 'dokterEmg.NIP', '=', 'dokter.NIP')
            ->where('pemeriksaanEmg.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}