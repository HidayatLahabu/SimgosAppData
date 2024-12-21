<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordFaktorRisikoModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'faktor_risiko';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.faktor_risiko as faktorRisiko')
            ->select([
                'faktorRisiko.ID as ID',
                'faktorRisiko.KUNJUNGAN as KUNJUNGAN',
                'faktorRisiko.HIPERTENSI as HIPERTENSI',
                'faktorRisiko.DIABETES_MELITUS as DIABETES_MELITUS',
                'faktorRisiko.PENYAKIT_JANTUNG as PENYAKIT_JANTUNG',
                'faktorRisiko.ASMA as ASMA',
                'faktorRisiko.STROKE as STROKE',
                'faktorRisiko.LIVER as LIVER',
                'faktorRisiko.GINJAL as GINJAL',
                'faktorRisiko.PENYAKIT_KEGANASAN_DAN_HIV as PENYAKIT_KEGANASAN_DAN_HIV',
                'faktorRisiko.DISLIPIDEMIA as DISLIPIDEMIA',
                'faktorRisiko.GAGAL_JANTUNG as GAGAL_JANTUNG',
                'faktorRisiko.SERANGAN_JANTUNG as SERANGAN_JANTUNG',
                'faktorRisiko.ROKOK as ROKOK',
                'faktorRisiko.MINUM_ALKOHOL as MINUM_ALKOHOL',
                'faktorRisiko.MINUMAN_ALKOHOL as MINUMAN_ALKOHOL',
                'faktorRisiko.MEROKOK as MEROKOK',
                'faktorRisiko.BEGADANG as BEGADANG',
                'faktorRisiko.SEKS_BEBAS as SEKS_BEBAS',
                'faktorRisiko.NAPZA_TAMPA_RESEP_DOKTER as NAPZA_TAMPA_RESEP_DOKTER',
                'faktorRisiko.MAKAN_MANIS_BERLEBIHAN as MAKAN_MANIS_BERLEBIHAN',
                'faktorRisiko.PERILAKU_LGBT as PERILAKU_LGBT',
                'faktorRisiko.NOTIFIKASI_PASANGAN_NP as NOTIFIKASI_PASANGAN_NP',
                'faktorRisiko.PENYAKIT_LAIN as PENYAKIT_LAIN',
                'faktorRisiko.PERILAKU_LAIN as PERILAKU_LAIN',
                'faktorRisiko.PERNAH_DIRAWAT_TIDAK as PERNAH_DIRAWAT_TIDAK',
                'faktorRisiko.PERNAH_DIRAWAT_KAPAN as PERNAH_DIRAWAT_KAPAN',
                'faktorRisiko.PERNAH_DIRAWAT_DIMANA as PERNAH_DIRAWAT_DIMANA',
                'faktorRisiko.PERNAH_DIRAWAT_DIAGNOSIS as PERNAH_DIRAWAT_DIAGNOSIS',
                'faktorRisiko.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'faktorRisiko.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'faktorRisiko.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('faktorRisiko.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}
