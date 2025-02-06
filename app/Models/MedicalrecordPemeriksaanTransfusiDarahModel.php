<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordPemeriksaanTransfusiDarahModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'pemeriksaan_transfusi_darah';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.pemeriksaan_transfusi_darah as pemeriksaanTransfusiDarah')
            ->select([
                'pemeriksaanTransfusiDarah.ID as ID',
                'pemeriksaanTransfusiDarah.KUNJUNGAN as KUNJUNGAN',
                'pemeriksaanTransfusiDarah.SESUAI_PERMINTAAN as SESUAI_PERMINTAAN',
                'pemeriksaanTransfusiDarah.JENIS_DARAH as JENIS_DARAH',
                'pemeriksaanTransfusiDarah.GOLOGAN_DARAH as GOLOGAN_DARAH',
                'pemeriksaanTransfusiDarah.RESUS as RESUS',
                'pemeriksaanTransfusiDarah.JUMLAH_BAG as JUMLAH_BAG',
                'pemeriksaanTransfusiDarah.JUMLAH_CC as JUMLAH_CC',
                'pemeriksaanTransfusiDarah.TANGGAL as TANGGAL',
                DB::raw('master.getNamaLengkapPegawai(pegawai.NIP) as OLEH'),
                'pemeriksaanTransfusiDarah.STATUS as STATUS',
                'transfusiDarahDetail.ID as DETAIL_ID',
                'transfusiDarahDetail.NOMOR_KANTONG as NOMOR_KANTONG',
                'transfusiDarahDetail.TANGGAL_KADALUARSA as TANGGAL_KADALUARSA',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'pemeriksaanTransfusiDarah.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('medicalrecord.pemeriksaan_transfusi_darah_detail', 'transfusiDarahDetail.TRANSFUSI_DARAH', '=', 'pemeriksaanTransfusiDarah.ID')
            ->where('pemeriksaanTransfusiDarah.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return $query;
    }
}
