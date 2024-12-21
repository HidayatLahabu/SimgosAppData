<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordBatukModel extends Model
{
    use HasFactory;

    // Koneksi ke database yang digunakan
    public $connection = "mysql11";

    // Menentukan tabel yang digunakan
    protected $table = 'batuk';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.batuk as batuk')
            ->select([
                'batuk.ID as ID',
                'batuk.KUNJUNGAN as KUNJUNGAN',
                'batuk.DEMAM as DEMAM',
                'batuk.DEMAM_KETERANGAN as DEMAM_KETERANGAN',
                'batuk.BERKERINGAT_MALAM_HARI_TANPA_AKTIFITAS as BERKERINGAT_MALAM_HARI_TANPA_AKTIFITAS',
                'batuk.BERKERINGAT_MALAM_HARI_TANPA_AKTIFITAS_KETERANGAN as BERKERINGAT_MALAM_HARI_TANPA_AKTIFITAS_KETERANGAN',
                'batuk.BEPERGIAN_DARI_DAERAH_WABAH as BEPERGIAN_DARI_DAERAH_WABAH',
                'batuk.BEPERGIAN_DARI_DAERAH_WABAH_KETERANGAN as BEPERGIAN_DARI_DAERAH_WABAH_KETERANGAN',
                'batuk.PEMAKAIAN_OBAT_JANGKA_PANJANG as PEMAKAIAN_OBAT_JANGKA_PANJANG',
                'batuk.PEMAKAIAN_OBAT_JANGKA_PANJANG_KETERANGAN as PEMAKAIAN_OBAT_JANGKA_PANJANG_KETERANGAN',
                'batuk.BERAT_BADAN_TURUN_TANPA_SEBAB as BERAT_BADAN_TURUN_TANPA_SEBAB',
                'batuk.BERAT_BADAN_TURUN_TANPA_SEBAB_KETERANGAN as BERAT_BADAN_TURUN_TANPA_SEBAB_KETERANGAN',
                'batuk.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'batuk.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'batuk.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('batuk.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}
