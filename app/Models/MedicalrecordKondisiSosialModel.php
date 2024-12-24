<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalrecordKondisiSosialModel extends Model
{
    use HasFactory;

    // Koneksi ke database yang digunakan
    public $connection = "mysql11";

    // Menentukan tabel yang digunakan
    protected $table = 'kondisi_sosial';

    /**
     * Mendapatkan data triage berdasarkan ID
     *
     * @param int $id
     * @return object|null
     */
    public static function getById($id)
    {
        // Melakukan query untuk mengambil data berdasarkan ID
        $query = DB::connection('mysql11')->table('medicalrecord.kondisi_sosial as kondisiSosial')
            ->select([
                'kondisiSosial.ID as ID_KONDISI_SOSIAL',
                'kondisiSosial.KUNJUNGAN as KUNJUNGAN',
                'kondisiSosial.TIDAK_ADA_KELAINAN as TIDAK_ADA_KELAINAN',
                'kondisiSosial.MARAH as MARAH',
                'kondisiSosial.CEMAS as CEMAS',
                'kondisiSosial.TAKUT as TAKUT',
                'kondisiSosial.BUNUH_DIRI as BUNUH_DIRI',
                'kondisiSosial.SEDIH as SEDIH',
                'kondisiSosial.LAINNYA as LAINNYA',
                'kondisiSosial.STATUS_MENTAL as STATUS_MENTAL',
                'kondisiSosial.MASALAH_PERILAKU as MASALAH_PERILAKU',
                'kondisiSosial.PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA as PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA',
                'kondisiSosial.HUBUNGAN_PASIEN_DENGAN_KELUARGA as HUBUNGAN_PASIEN_DENGAN_KELUARGA',
                'kondisiSosial.TEMPAT_TINGGAL as TEMPAT_TINGGAL',
                'kondisiSosial.TEMPAT_TINGGAL_LAINNYA as TEMPAT_TINGGAL_LAINNYA',
                'kondisiSosial.KEBIASAAN_BERIBADAH_TERATUR as KEBIASAAN_BERIBADAH_TERATUR',
                'kondisiSosial.NILAI_KEPERCAYAAN as NILAI_KEPERCAYAAN',
                'kondisiSosial.NILAI_KEPERCAYAAN_DESKRIPSI as NILAI_KEPERCAYAAN_DESKRIPSI',
                'kondisiSosial.PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA as PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA',
                'kondisiSosial.PENGHASILAN_PERBULAN as PENGHASILAN_PERBULAN',
                'kondisiSosial.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawaiKondisiSosial.GELAR_DEPAN, " ", pegawaiKondisiSosial.NAMA, " ", pegawaiKondisiSosial.GELAR_BELAKANG) as KONDISI_SOSIAL_OLEH'),
                'kondisiSosial.STATUS as STATUS_KONDISI_SOSIAL',
                'hubunganPsikososial.ID as ID_HUBUNGAN_PSIKOSOSIAL',
                'hubunganPsikososial.KECEMASAN_PASIEN_ATAU_KERABAT as KECEMASAN_PASIEN_ATAU_KERABAT',
                'hubunganPsikososial.KETERANGAN_KECEMASAN_PASIEN_ATAU_KERABAT as KETERANGAN_KECEMASAN_PASIEN_ATAU_KERABAT',
                'hubunganPsikososial.KEBUTUHAN_DAN_DUKUNGAN_SPIRITUAL as KEBUTUHAN_DAN_DUKUNGAN_SPIRITUAL',
                'hubunganPsikososial.KETERANGAN_KEBUTUHAN_DAN_DUKUNGAN_SPIRITUAL as KETERANGAN_KEBUTUHAN_DAN_DUKUNGAN_SPIRITUAL',
                'hubunganPsikososial.DUKUNGAN_DARI_TIM as DUKUNGAN_DARI_TIM',
                'hubunganPsikososial.KETERANGAN_DUKUNGAN_DARI_TIM as KETERANGAN_DUKUNGAN_DARI_TIM',
                'hubunganPsikososial.INDIKASI_TRADISI_KEAGAMAAN as INDIKASI_TRADISI_KEAGAMAAN',
                'hubunganPsikososial.KETERANGAN_INDIKASI_TRADISI_KEAGAMAAN as KETERANGAN_INDIKASI_TRADISI_KEAGAMAAN',
                'hubunganPsikososial.INDIKASI_KEBUTUHAN_KHUSUS as INDIKASI_KEBUTUHAN_KHUSUS',
                'hubunganPsikososial.KETERANGAN_INDIKASI_KEBUTUHAN_KHUSUS as KETERANGAN_INDIKASI_KEBUTUHAN_KHUSUS',
                'hubunganPsikososial.PILIHAN_HIDUP_PASIEN as PILIHAN_HIDUP_PASIEN',
                'hubunganPsikososial.TANGGAL as PILIHAN_HIDUP_PASIEN',
                DB::raw('CONCAT(pegawaiHubunganPsikososial.GELAR_DEPAN, " ", pegawaiHubunganPsikososial.NAMA, " ", pegawaiHubunganPsikososial.GELAR_BELAKANG) as HUBUNGAN_PSIKOSOSIAL_OLEH'),
                'hubunganPsikososial.STATUS as STATUS_HUBUNGAN_PSIKOSOSIAL',
            ])
            ->leftJoin('aplikasi.pengguna as penggunaKondisiSosial', 'penggunaKondisiSosial.ID', '=', 'kondisiSosial.OLEH')
            ->leftJoin('master.pegawai as pegawaiKondisiSosial', 'pegawaiKondisiSosial.NIP', '=', 'penggunaKondisiSosial.NIP')
            ->leftJoin('medicalrecord.hubungan_psikososial_end_of_life as hubunganPsikososial', 'hubunganPsikososial.KUNJUNGAN', '=', 'kondisiSosial.KUNJUNGAN')
            ->leftJoin('aplikasi.pengguna as penggunaEndOfLife', 'penggunaEndOfLife.ID', '=', 'hubunganPsikososial.OLEH')
            ->leftJoin('master.pegawai as pegawaiHubunganPsikososial', 'pegawaiHubunganPsikososial.NIP', '=', 'penggunaEndOfLife.NIP')
            ->where('kondisiSosial.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Jika data tidak ditemukan, default atau error
        if (!$query) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return $query;
    }
}