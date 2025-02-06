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
                'permasalahanGizi.*',
                DB::raw('master.getNamaLengkapPegawai(pegawai.NIP) as OLEH'),
                DB::raw('master.getNamaLengkapPegawai(pegawaiValidasi.NIP) as USER_VALIDASI'),
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
            return response()->json(['message' => 'Data not found'], 404);
        }

        return $query;
    }
}