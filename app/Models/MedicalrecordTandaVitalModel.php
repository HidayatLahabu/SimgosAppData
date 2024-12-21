<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordTandaVitalModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'tanda_vital';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.tanda_vital as tandaVital')
            ->select([
                'tandaVital.ID as ID',
                'tandaVital.KUNJUNGAN as KUNJUNGAN',
                'tandaVital.KEADAAN_UMUM as KEADAAN_UMUM',
                'tandaVital.KESADARAN as KESADARAN',
                'tandaVital.SISTOLIK as SISTOLIK',
                'tandaVital.DISTOLIK as DISTOLIK',
                'tandaVital.FREKUENSI_NADI as FREKUENSI_NADI',
                'tandaVital.FREKUENSI_NAFAS as FREKUENSI_NAFAS',
                'tandaVital.SUHU as SUHU',
                'tandaVital.SATURASI_O2 as SATURASI_O2',
                'tandaVital.MOTORIK as MOTORIK',
                'tandaVital.GCS as GCS',
                'tandaVital.EWSS as EWSS',
                'tandaVital.UMUR as UMUR',
                'tandaVital.PEWSS as PEWSS',
                'tandaVital.TINGKAT_KESADARAN as TINGKAT_KESADARAN',
                'tandaVital.WAKTU_PEMERIKSAAN as WAKTU_PEMERIKSAAN',
                'tandaVital.ALAT_BANTU_NAFAS as ALAT_BANTU_NAFAS',
                'tandaVital.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'tandaVital.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'tandaVital.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('tandaVital.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}
