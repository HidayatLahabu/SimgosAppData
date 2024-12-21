<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordPermasalahanGiziModel extends Model
{
    use HasFactory;

    // Koneksi ke database yang digunakan
    public $connection = "mysql11";

    // Menentukan tabel yang digunakan
    protected $table = 'permasalahan_gizi';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.permasalahan_gizi as permasalahanGizi')
            ->select([
                'permasalahanGizi.ID as ID',
                'permasalahanGizi.KUNJUNGAN as KUNJUNGAN',
                'permasalahanGizi.BERAT_BADAN_SIGNIFIKAN as BERAT_BADAN_SIGNIFIKAN',
                'permasalahanGizi.PERUBAHAN_BERAT_BADAN as PERUBAHAN_BERAT_BADAN',
                'permasalahanGizi.INTAKE_MAKANAN as INTAKE_MAKANAN',
                'permasalahanGizi.KONDISI_KHUSUS as KONDISI_KHUSUS',
                'permasalahanGizi.SKOR as SKOR',
                'permasalahanGizi.STATUS_SKOR as STATUS_SKOR',
                'permasalahanGizi.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'permasalahanGizi.STATUS_VALIDASI as STATUS_VALIDASI',
                'permasalahanGizi.TANGGAL_VALIDASI as TANGGAL_VALIDASI',
                DB::raw('CONCAT(pegawaiValidasi.GELAR_DEPAN, " ", pegawaiValidasi.NAMA, " ", pegawaiValidasi.GELAR_BELAKANG) as USER_VALIDASI'),
                'permasalahanGizi.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'permasalahanGizi.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('aplikasi.pengguna as penggunaValidasi', 'penggunaValidasi.ID', '=', 'permasalahanGizi.USER_VALIDASI')
            ->leftJoin('master.pegawai as pegawaiValidasi', 'pegawaiValidasi.NIP', '=', 'penggunaValidasi.NIP')
            ->where('permasalahanGizi.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}
