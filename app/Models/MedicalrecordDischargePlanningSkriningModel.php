<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalrecordDischargePlanningSkriningModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'discharge_planning_skrining';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.discharge_planning_skrining as dischargePlanningSkrining')
            ->select([
                'dischargePlanningSkrining.*',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'dischargePlanningSkrining.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('dischargePlanningSkrining.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return $query;
    }
}