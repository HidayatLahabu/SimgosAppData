<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordStatusFungsionalModel extends Model
{
    use HasFactory;

    // Koneksi ke database yang digunakan
    public $connection = "mysql11";

    // Menentukan tabel yang digunakan
    protected $table = 'status_fungsional';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.status_fungsional as statusFungsional')
            ->select([
                'statusFungsional.ID as ID',
                'statusFungsional.KUNJUNGAN as KUNJUNGAN',
                'statusFungsional.TANPA_ALAT_BANTU as TANPA_ALAT_BANTU',
                'statusFungsional.TONGKAT as TONGKAT',
                'statusFungsional.KURSI_RODA as KURSI_RODA',
                'statusFungsional.BRANKARD as BRANKARD',
                'statusFungsional.WALKER as WALKER',
                'statusFungsional.ALAT_BANTU as ALAT_BANTU',
                'statusFungsional.CACAT_TUBUH_TIDAK as CACAT_TUBUH_TIDAK',
                'statusFungsional.CACAT_TUBUH_YA as CACAT_TUBUH_YA',
                'statusFungsional.KET_CACAT_TUBUH as KET_CACAT_TUBUH',
                'statusFungsional.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'statusFungsional.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'statusFungsional.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('statusFungsional.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}
