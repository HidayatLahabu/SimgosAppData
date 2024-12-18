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
        $query = DB::connection('mysql5')->table('medicalrecord.asuhan_keperawatan as askep')
            ->select([
                'askep.ID as ID',
                'askep.KUNJUNGAN as KUNJUNGAN',
                DB::raw('JSON_UNQUOTE(askep.SUBJECKTIF) as SUBJECKTIF'),
                DB::raw('JSON_UNQUOTE(askep.OBJEKTIF) as OBJEKTIF'),
                'askep.SUBJECT_MINOR as SUBJECT_MINOR',
                'askep.OBJECT_MINOR as OBJECT_MINOR',
                'askep.FAKTOR_RESIKO as FAKTOR_RESIKO',
                'askep.DIAGNOSA as DIAGNOSA',
                'askep.DESK_DIAGNOSA as DESK_DIAGNOSA',
                'askep.LAMA_INTEVENSI as LAMA_INTEVENSI',
                'askep.JENIS_LAMA_INTERVENSI as JENIS_LAMA_INTERVENSI',
                'askep.TUJUAN as TUJUAN',
                'askep.DESK_TUJUAN as DESK_TUJUAN',
                'askep.KRITERIA_HASIL as KRITERIA_HASIL',
                'askep.INTERVENSI as INTERVENSI',
                'askep.DESK_INTERVENSI as DESK_INTERVENSI',
                DB::raw('JSON_UNQUOTE(askep.OBSERVASI) as OBSERVASI'),
                DB::raw('JSON_UNQUOTE(askep.THEURAPEUTIC) as THEURAPEUTIC'),
                DB::raw('JSON_UNQUOTE(askep.EDUKASI) as EDUKASI'),
                DB::raw('JSON_UNQUOTE(askep.KOLABORASI) as KOLABORASI'),
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'askep.USER_VERIFIKASI as USER_VERIFIKASI',
                'askep.TANGGAL_VERIFIKASI as TANGGAL_VERIFIKASI',
                'askep.TANGGAL_INPUT as TANGGAL_INPUT',
                'askep.TANGGAL as TANGGAL',
                'askep.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'askep.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('askep.ID', $id)
            ->distinct()
            ->first();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}