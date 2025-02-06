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
                DB::raw('master.getNamaLengkapPegawai(tenagaMedis.NIP) as TENAGA_MEDIS'),
                DB::raw('master.getNamaLengkapPegawai(pegawai.NIP) as OLEH'),
                'verifikasi.TANGGAL as TANGGAL_VERIFIKASI',
                DB::raw('master.getNamaLengkapPegawai(pegawaiVerifikasi.NIP) as VERIFIKASI_OLEH'),
            ])
            ->leftJoin('medicalrecord.verifikasi_cppt as verifikasi', 'verifikasi.ID', '=', 'cppt.VERIFIKASI')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'cppt.TENAGA_MEDIS')
            ->leftJoin('master.pegawai as tenagaMedis', 'tenagaMedis.NIP', '=', 'dokter.NIP')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'cppt.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('aplikasi.pengguna as petugasVerifikasi', 'petugasVerifikasi.ID', '=', 'verifikasi.OLEH')
            ->leftJoin('master.pegawai as pegawaiVerifikasi', 'pegawaiVerifikasi.NIP', '=', 'petugasVerifikasi.NIP')
            ->leftJoin('master.referensi as subDevisi', function ($join) {
                $join->on('subDevisi.ID', '=', 'cppt.SUB_DEVISI')
                    ->where('subDevisi.JENIS', '=', 26);
            })
            ->where('cppt.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        // Bersihkan data dari tag HTML jika ditemukan
        if ($query) {
            $query->SUBYEKTIF = html_entity_decode(strip_tags($query->SUBYEKTIF));
            $query->OBYEKTIF = html_entity_decode(strip_tags($query->OBYEKTIF));
            $query->ASSESMENT = html_entity_decode(strip_tags($query->ASSESMENT));
            $query->PLANNING = html_entity_decode(strip_tags($query->PLANNING));
            $query->INSTRUKSI = html_entity_decode(strip_tags($query->INSTRUKSI));
        }

        return $query;
    }
}
