<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordCpptModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'cppt';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.cppt as cppt')
            ->select([
                'cppt.*',
                DB::raw('CONCAT(tenagaMedis.GELAR_DEPAN, " ", tenagaMedis.NAMA, " ", tenagaMedis.GELAR_BELAKANG) as TENAGA_MEDIS'),
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
            ])
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'cppt.TENAGA_MEDIS')
            ->leftJoin('master.pegawai as tenagaMedis', 'tenagaMedis.NIP', '=', 'dokter.NIP')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'cppt.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.referensi as subDevisi', function ($join) {
                $join->on('subDevisi.ID', '=', 'cppt.SUB_DEVISI')
                    ->where('subDevisi.JENIS', '=', 26);
            })
            ->where('cppt.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}