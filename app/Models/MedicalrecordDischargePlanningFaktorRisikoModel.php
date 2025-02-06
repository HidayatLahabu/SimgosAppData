<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordDischargePlanningFaktorRisikoModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'discharge_planning_faktor_risiko';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.discharge_planning_faktor_risiko as dischargePlanningFaktorRisiko')
            ->select([
                'dischargePlanningSkrining.*',
                DB::raw('master.getNamaLengkapPegawai(pegawai.NIP) as OLEH'),
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'dischargePlanningFaktorRisiko.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('dischargePlanningFaktorRisiko.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return $query;
    }
}
