<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalrecordDiagnosaModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'diagnosa';

    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.diagnosa as diagnosa')
            ->select([
                'diagnosa.ID as ID',
                'diagnosa.NOPEN as NOMOR_PENDAFTARAN',
                'kunjungan.NOMOR as NOMOR_KUNJUNGAN',
                'diagnosa.KODE as KODE',
                'diagnosa.DIAGNOSA as DIAGNOSA',
                'diagnosa.UTAMA as UTAMA',
                'diagnosa.INACBG as INACBG',
                'diagnosa.BARU as BARU',
                'diagnosa.TANGGAL as TANGGAL',
                DB::raw('master.getNamaLengkapPegawai(pegawai.NIP) as OLEH'),
                'diagnosa.STATUS as STATUS',
                'diagnosa.INA_GROUPER as INA_GROUPER',
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOPEN', '=', 'diagnosa.NOPEN')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'diagnosa.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('diagnosa.ID', $id)
            ->distinct()
            ->first();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return $query;
    }
}