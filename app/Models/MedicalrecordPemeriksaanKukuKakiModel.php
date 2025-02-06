<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordPemeriksaanKukuKakiModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'pemeriksaan_kuku_kaki';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.pemeriksaan_kuku_kaki as pemeriksaanKukuKaki')
            ->select([
                'pemeriksaanKukuKaki.*',
                DB::raw('master.getNamaLengkapPegawai(pegawai.NIP) as OLEH'),
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'pemeriksaanKukuKaki.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('pemeriksaanKukuKaki.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return $query;
    }
}
