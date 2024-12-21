<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordRiwayatTuberkulosisModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'riwayat_penyakit_tb';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.riwayat_penyakit_tb as riwayatTuberkulosis')
            ->select([
                'riwayatTuberkulosis.ID as ID',
                'riwayatTuberkulosis.KUNJUNGAN as KUNJUNGAN',
                'riwayatTuberkulosis.RIWAYAT as RIWAYAT',
                'riwayatTuberkulosis.TAHUN as TAHUN',
                'riwayatTuberkulosis.BEROBAT as BEROBAT',
                'riwayatTuberkulosis.SPUTUM as SPUTUM',
                'riwayatTuberkulosis.TANGGAL_PEMERIKSAAN_SPUTUM as TANGGAL_PEMERIKSAAN_SPUTUM',
                'riwayatTuberkulosis.TEST_CEPAT_MOLEKULER as TEST_CEPAT_MOLEKULER',
                'riwayatTuberkulosis.TANGGAL_TEST_CEPAT as TANGGAL_TEST_CEPAT',
                'riwayatTuberkulosis.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'riwayatTuberkulosis.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'riwayatTuberkulosis.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('riwayatTuberkulosis.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}
