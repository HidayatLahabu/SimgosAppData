<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordPemeriksaanEkgModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'pemeriksaan_ekg';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.pemeriksaan_ekg as pemeriksaanEkg')
            ->select([
                'pemeriksaanEkg.*',
                DB::raw('master.getNamaLengkapPegawai(tenagaMedis.NIP) as TENAGA_MEDIS'),
                DB::raw('master.getNamaLengkapPegawai(pegawai.NIP) as OLEH'),
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'pemeriksaanEkg.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.pegawai as tenagaMedis', 'tenagaMedis.ID', '=', 'pemeriksaanEkg.TENAGA_MEDIS')
            ->where('pemeriksaanEkg.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return $query;
    }
}
