<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordPemeriksaanMataModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'pemeriksaan_mata';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.pemeriksaan_mata as pemeriksaanMata')
            ->select([
                'pemeriksaanMata.ID as ID',
                'pemeriksaanMata.KUNJUNGAN as KUNJUNGAN',
                'pemeriksaanMata.PENDAFTARAN as PENDAFTARAN',
                'pemeriksaanMata.ANEMIS as ANEMIS',
                'pemeriksaanMata.IKTERUS as IKTERUS',
                'pemeriksaanMata.PUPIL_ISOKOR as PUPIL_ISOKOR',
                'pemeriksaanMata.PUPIL_ANISOKOR as PUPIL_ANISOKOR',
                'pemeriksaanMata.DIAMETER_ISIAN as DIAMETER_ISIAN',
                'pemeriksaanMata.DIAMETER_MM as DIAMETER_MM',
                'pemeriksaanMata.UDEM as UDEM',
                'pemeriksaanMata.ADA_KELAINAN as ADA_KELAINAN',
                'pemeriksaanMata.DESKRIPSI as DESKRIPSI',
                'pemeriksaanMata.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'pemeriksaanMata.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'pemeriksaanMata.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('pemeriksaanMata.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}