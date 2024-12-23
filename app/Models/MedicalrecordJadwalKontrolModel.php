<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordJadwalKontrolModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'jadwal_kontrol';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.jadwal_kontrol as jadwalKontrol')
            ->select([
                'jadwalKontrol.*',
                'tujuan.DESKRIPSI as TUJUAN',
                'ruangan.DESKRIPSI as RUANGAN',
                DB::raw('CONCAT(dokter.GELAR_DEPAN, " ", dokter.NAMA, " ", dokter.GELAR_BELAKANG) as DOKTER'),
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH')
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'jadwalKontrol.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as tenagaMedis', 'tenagaMedis.ID', '=', 'jadwalKontrol.DOKTER')
            ->leftJoin('master.pegawai as dokter', 'dokter.NIP', '=', 'tenagaMedis.NIP')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'jadwalKontrol.RUANGAN')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'jadwalKontrol.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.referensi as tujuan', function ($join) {
                $join->on('tujuan.ID', '=', 'jadwalKontrol.TUJUAN')
                    ->where('tujuan.JENIS', '=', 26);
            })
            ->where('jadwalKontrol.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}