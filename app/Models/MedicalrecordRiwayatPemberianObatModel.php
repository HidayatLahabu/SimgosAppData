<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordRiwayatPemberianObatModel extends Model
{
    use HasFactory;

    // Koneksi ke database yang digunakan
    public $connection = "mysql11";

    // Menentukan tabel yang digunakan
    protected $table = 'riwayat_pemberian_obat';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.riwayat_pemberian_obat as riwayatPemberianObat')
            ->select([
                'riwayatPemberianObat.*',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'riwayatPemberianObat.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.frekuensi_aturan_resep as frekuensiAturanResep', 'frekuensiAturanResep.ID', '=', 'riwayatPemberianObat.FREKUENSI')
            ->leftJoin('master.referensi as ruteObat', function ($join) {
                $join->on('riwayatPemberianObat.RUTE', '=', 'ruteObat.ID')
                    ->where('ruteObat.JENIS', '=', 217);
            })
            ->where('riwayatPemberianObat.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}