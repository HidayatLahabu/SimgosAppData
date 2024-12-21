<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordEdukasiEndOfLifeModel extends Model
{
    use HasFactory;

    // Koneksi ke database yang digunakan
    public $connection = "mysql11";

    // Menentukan tabel yang digunakan
    protected $table = 'edukasi_end_of_life';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.edukasi_end_of_life as edukasiEndOfLife')
            ->select([
                'edukasiEndOfLife.ID as ID',
                'edukasiEndOfLife.KUNJUNGAN as KUNJUNGAN',
                'edukasiEndOfLife.MENGETAHUI_DIAGNOSA as MENGETAHUI_DIAGNOSA',
                'edukasiEndOfLife.MENGETAHUI_PROGNOSIS as MENGETAHUI_PROGNOSIS',
                'edukasiEndOfLife.MENGETAHUI_TUJUAN_PERAWATAN as MENGETAHUI_TUJUAN_PERAWATAN',
                'edukasiEndOfLife.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'edukasiEndOfLife.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'edukasiEndOfLife.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('edukasiEndOfLife.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}
