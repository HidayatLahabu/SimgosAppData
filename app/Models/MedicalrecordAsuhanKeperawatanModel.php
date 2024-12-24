<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordAsuhanKeperawatanModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'asuhan_keperawatan';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.asuhan_keperawatan as askep')
            ->select([
                'askep.*',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH')
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'askep.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('askep.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        if ($query) {
            $query->SUBJECKTIF = implode(', ', json_decode($query->SUBJECKTIF, true));
            $query->OBJEKTIF = implode(', ', json_decode($query->OBJEKTIF, true));
            $query->OBSERVASI = implode(', ', json_decode($query->OBSERVASI, true));
            $query->THEURAPEUTIC = implode(', ', json_decode($query->THEURAPEUTIC, true));
            $query->EDUKASI = implode(', ', json_decode($query->EDUKASI, true));
            $query->KOLABORASI = implode(', ', json_decode($query->KOLABORASI, true));
            $query->PENYEBAP = implode(', ', json_decode($query->PENYEBAP, true));
        }

        return $query;
    }
}