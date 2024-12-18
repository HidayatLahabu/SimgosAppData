<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordTriageModel extends Model
{
    use HasFactory;

    // Koneksi ke database yang digunakan
    public $connection = "mysql11";

    // Menentukan tabel yang digunakan
    protected $table = 'triage';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::table('medicalrecord.triage as triage')
            ->select([
                'triage.ID as ID',
                'triage.KUNJUNGAN as KUNJUNGAN',
                'triage.NIK as NIK',
                'triage.TANGGAL_LAHIR as TANGGAL_LAHIR',
                'triage.JENIS_KELAMIN as JENIS_KELAMIN',
                'triage.TANGGAL as TANGGAL',
                DB::raw('JSON_UNQUOTE(triage.KEDATANGAN) as KEDATANGAN'),
                DB::raw('JSON_UNQUOTE(triage.KASUS) as KASUS'),
                DB::raw('JSON_UNQUOTE(triage.ANAMNESE) as ANAMNESE'),
                DB::raw('JSON_UNQUOTE(triage.TANDA_VITAL) as TANDA_VITAL'),
                DB::raw('JSON_UNQUOTE(triage.OBGYN) as OBGYN'),
                DB::raw('JSON_UNQUOTE(triage.KEBUTUHAN_KHUSUS) as KEBUTUHAN_KHUSUS'),
                'triage.KATEGORI_PEMERIKSAAN as KATEGORI_PEMERIKSAAN',
                DB::raw('JSON_UNQUOTE(triage.RESUSITASI) as RESUSITASI'),
                DB::raw('JSON_UNQUOTE(triage.EMERGENCY) as EMERGENCY'),
                DB::raw('JSON_UNQUOTE(triage.URGENT) as URGENT'),
                DB::raw('JSON_UNQUOTE(triage.LESS_URGENT) as LESS_URGENT'),
                DB::raw('JSON_UNQUOTE(triage.NON_URGENT) as NON_URGENT'),
                DB::raw('JSON_UNQUOTE(triage.DOA) as DOA'),
                'triage.KRITERIA as KRITERIA',
                'triage.HANDOVER as HANDOVER',
                'triage.PLAN as PLAN',
                DB::raw('CONCAT(COALESCE(pegawai.GELAR_DEPAN, ""), " ", COALESCE(pegawai.NAMA, ""), " ", COALESCE(pegawai.GELAR_BELAKANG, "")) as OLEH'),
                'triage.TANGGAL_FINAL as TANGGAL_FINAL',
                'triage.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'triage.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('triage.ID', $id)
            ->distinct()
            ->first();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}