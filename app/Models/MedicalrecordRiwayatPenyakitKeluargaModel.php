<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordRiwayatPenyakitKeluargaModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'riwayat_penyakit_keluarga';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.riwayat_penyakit_keluarga as rpk')
            ->select([
                'rpk.ID as ID',
                'rpk.KUNJUNGAN as KUNJUNGAN',
                'rpk.HIPERTENSI as HIPERTENSI',
                'rpk.DIABETES_MELITUS as DIABETES_MELITUS',
                'rpk.PENYAKIT_JANTUNG as PENYAKIT_JANTUNG',
                'rpk.HIPERTENSI as HIPERTENSI',
                'rpk.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'rpk.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'rpk.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('rpk.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}
