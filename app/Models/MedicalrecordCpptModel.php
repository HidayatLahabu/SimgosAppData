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
                'cppt.ID as ID',
                'cppt.KUNJUNGAN as KUNJUNGAN',
                'cppt.TANGGAL as TANGGAL',
                'cppt.SUBYEKTIF as SUBYEKTIF',
                'cppt.OBYEKTIF as OBYEKTIF',
                'cppt.ASSESMENT as ASSESMENT',
                'cppt.PLANNING as PLANNING',
                'cppt.INSTRUKSI as INSTRUKSI',
                'cppt.TULIS as TULIS',
                'cppt.JENIS as JENIS',
                DB::raw('CONCAT(tenagaMedis.GELAR_DEPAN, " ", tenagaMedis.NAMA, " ", tenagaMedis.GELAR_BELAKANG) as TENAGA_MEDIS'),
                'cppt.STATUS_TBAK as STATUS_TBAK',
                'cppt.STATUS_SBAR as STATUS_SBAR',
                'cppt.BACA as BACA',
                'cppt.KONFIRMASI as KONFIRMASI',
                'cppt.ADIME as ADIME',
                'cppt.DOKTER_TBAK_OR_SBAR as DOKTER_TBAK_OR_SBAR',
                'cppt.RENCANA_PULANG as RENCANA_PULANG',
                'cppt.TANGGAL_RENCANA_PULANG as TANGGAL_RENCANA_PULANG',
                'subDevisi.DESKRIPSI as SUB_DEVISI',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'cppt.VERIFIKASI as VERIFIKASI',
                'cppt.STATUS as STATUS',
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
