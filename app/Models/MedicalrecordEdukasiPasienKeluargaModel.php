<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordEdukasiPasienKeluargaModel extends Model
{
    use HasFactory;

    // Koneksi ke database yang digunakan
    public $connection = "mysql11";

    // Menentukan tabel yang digunakan
    protected $table = 'edukasi_pasien_keluarga';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.edukasi_pasien_keluarga as edukasiPasienKeluarga')
            ->select([
                'edukasiPasienKeluarga.ID as ID',
                'edukasiPasienKeluarga.KUNJUNGAN as KUNJUNGAN',
                'edukasiPasienKeluarga.KESEDIAAN as KESEDIAAN',
                'edukasiPasienKeluarga.HAMBATAN as HAMBATAN',
                'edukasiPasienKeluarga.HAMBATAN_PENDENGARAN as HAMBATAN_PENDENGARAN',
                'edukasiPasienKeluarga.HAMBATAN_PENGLIHATAN as HAMBATAN_PENGLIHATAN',
                'edukasiPasienKeluarga.HAMBATAN_KOGNITIF as HAMBATAN_KOGNITIF',
                'edukasiPasienKeluarga.HAMBATAN_FISIK as HAMBATAN_FISIK',
                'edukasiPasienKeluarga.HAMBATAN_BUDAYA as HAMBATAN_BUDAYA',
                'edukasiPasienKeluarga.HAMBATAN_EMOSI as HAMBATAN_EMOSI',
                'edukasiPasienKeluarga.HAMBATAN_BAHASA as HAMBATAN_BAHASA',
                'edukasiPasienKeluarga.HAMBATAN_LAINNYA as HAMBATAN_LAINNYA',
                'edukasiPasienKeluarga.PENERJEMAH as PENERJEMAH',
                'edukasiPasienKeluarga.EDUKASI_DIAGNOSA as EDUKASI_DIAGNOSA',
                'edukasiPasienKeluarga.EDUKASI_PENYAKIT as EDUKASI_PENYAKIT',
                'edukasiPasienKeluarga.EDUKASI_REHAB_MEDIK as EDUKASI_REHAB_MEDIK',
                'edukasiPasienKeluarga.EDUKASI_HKP as EDUKASI_HKP',
                'edukasiPasienKeluarga.EDUKASI_OBAT as EDUKASI_OBAT',
                'edukasiPasienKeluarga.EDUKASI_NYERI as EDUKASI_NYERI',
                'edukasiPasienKeluarga.EDUKASI_NUTRISI as EDUKASI_NUTRISI',
                'edukasiPasienKeluarga.EDUKASI_PENGGUNAAN_ALAT as EDUKASI_PENGGUNAAN_ALAT',
                'edukasiPasienKeluarga.EDUKASI_HAK_BERPARTISIPASI as EDUKASI_HAK_BERPARTISIPASI',
                'edukasiPasienKeluarga.EDUKASI_PROSEDURE_PENUNJANG as EDUKASI_PROSEDURE_PENUNJANG',
                'edukasiPasienKeluarga.EDUKASI_PEMBERIAN_INFORMED_CONSENT as EDUKASI_PEMBERIAN_INFORMED_CONSENT',
                'edukasiPasienKeluarga.EDUKASI_PENUNDAAN_PELAYANAN as EDUKASI_PENUNDAAN_PELAYANAN',
                'edukasiPasienKeluarga.EDUKASI_KELAMBATAN_PELAYANAN as EDUKASI_KELAMBATAN_PELAYANAN',
                'edukasiPasienKeluarga.EDUKASI_CUCI_TANGAN as EDUKASI_CUCI_TANGAN',
                'edukasiPasienKeluarga.EDUKASI_BAHAYA_MEROKO as EDUKASI_BAHAYA_MEROKO',
                'edukasiPasienKeluarga.EDUKASI_RUJUKAN_PASIEN as EDUKASI_RUJUKAN_PASIEN',
                'edukasiPasienKeluarga.EDUKASI_PERENCANAAN_PULANG as EDUKASI_PERENCANAAN_PULANG',
                'edukasiPasienKeluarga.STATUS_LAIN as STATUS_LAIN',
                'edukasiPasienKeluarga.DESKRIPSI_LAINYA as DESKRIPSI_LAINYA',
                'edukasiPasienKeluarga.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'edukasiPasienKeluarga.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'edukasiPasienKeluarga.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('edukasiPasienKeluarga.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Triage not found'], 404);
        }

        return $query;
    }
}
