<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordAnamnesisDiperolehModel extends Model
{
    use HasFactory;

    // Koneksi ke database yang digunakan
    public $connection = "mysql11";

    // Menentukan tabel yang digunakan
    protected $table = 'anamnesis_diperoleh';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql5')->table('medicalrecord.anamnesis_diperoleh as anamnesisDiperoleh')
            ->select([
                'anamnesisDiperoleh.ID as ID',
                'anamnesisDiperoleh.KUNJUNGAN as KUNJUNGAN',
                'anamnesisDiperoleh.AUTOANAMNESIS as AUTOANAMNESIS',
                'anamnesisDiperoleh.ALLOANAMNESIS as ALLOANAMNESIS',
                'anamnesisDiperoleh.DARI as DARI',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'anamnesisDiperoleh.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'anamnesisDiperoleh.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('anamnesisDiperoleh.ID', $id)
            ->distinct()
            ->first();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}