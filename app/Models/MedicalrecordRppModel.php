<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalrecordRppModel extends Model
{
    use HasFactory;

    // Koneksi ke database yang digunakan
    public $connection = "mysql11";

    // Menentukan tabel yang digunakan
    protected $table = 'rpp';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql5')->table('medicalrecord.rpp as rpp')
            ->select([
                'rpp.ID as ID',
                'rpp.KUNJUNGAN as KUNJUNGAN',
                'rpp.SNOMED_CT_ID as SNOMED_CT_ID',
                'rpp.DESKRIPSI as DESKRIPSI',
                'rpp.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'rpp.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'rpp.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('rpp.ID', $id)
            ->distinct()
            ->first();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}