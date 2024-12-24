<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordPenilaianBallanceCairanModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'penilaian_ballance_cairan';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.penilaian_ballance_cairan as penilaianBallanceCairan')
            ->select([
                'penilaianBallanceCairan.*',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'penilaianBallanceCairanDetail.KELOMPOK as KELOMPOK',
                'penilaianBallanceCairanDetail.DESKRIPSI as DESKRIPSI',
                'penilaianBallanceCairanDetail.REFERENSI as REFERENSI',
                'penilaianBallanceCairanDetail.JENIS as JENIS',
                'penilaianBallanceCairanDetail.JUMLAH as JUMLAH',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'penilaianBallanceCairan.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('medicalrecord.penilaian_ballance_cairan_detail as penilaianBallanceCairanDetail', 'penilaianBallanceCairanDetail.BALANCE_CAIRAN', '=', 'penilaianBallanceCairan.ID')
            ->where('penilaianBallanceCairan.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return $query;
    }
}