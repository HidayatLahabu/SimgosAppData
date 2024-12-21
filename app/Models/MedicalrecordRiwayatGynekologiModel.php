<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordRiwayatGynekologiModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'riwayat_gynekologi';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.riwayat_gynekologi as riwayatGynekologi')
            ->select([
                'riwayatGynekologi.ID as ID',
                'riwayatGynekologi.KUNJUNGAN as KUNJUNGAN',
                'riwayatGynekologi.INFERTILITAS as INFERTILITAS',
                'riwayatGynekologi.INFEKSI_VIRUS as INFEKSI_VIRUS',
                'riwayatGynekologi.PENYAKIT_MENULAR_SEKSUAL as PENYAKIT_MENULAR_SEKSUAL',
                'riwayatGynekologi.CERVISITIS_CRONIS as CERVISITIS_CRONIS',
                'riwayatGynekologi.ENDOMETRIOSIS as ENDOMETRIOSIS',
                'riwayatGynekologi.MYOMA as MYOMA',
                'riwayatGynekologi.POLIP_SERVIX as POLIP_SERVIX',
                'riwayatGynekologi.KANKER_KANDUNGAN as KANKER_KANDUNGAN',
                'riwayatGynekologi.MINUMAN_ALKOHOL as MINUMAN_ALKOHOL',
                'riwayatGynekologi.PERKOSAAN as PERKOSAAN',
                'riwayatGynekologi.OPERASI_KANDUNGAN as OPERASI_KANDUNGAN',
                'riwayatGynekologi.POST_COINTAL_BLEEDING as POST_COINTAL_BLEEDING',
                'riwayatGynekologi.FLOUR_ALBUS as FLOUR_ALBUS',
                'riwayatGynekologi.LAINYA as LAINYA',
                'riwayatGynekologi.KETERANGAN_LAINNYA as KETERANGAN_LAINNYA',
                'riwayatGynekologi.GATAL as GATAL',
                'riwayatGynekologi.BERBAU as BERBAU',
                'riwayatGynekologi.WARNAH as WARNAH',
                'riwayatGynekologi.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'riwayatGynekologi.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'riwayatGynekologi.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('riwayatGynekologi.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}
