<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{

    public function getTriage($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'triage.ID as id',
            ])
            ->leftJoin('medicalrecord.triage as triage', 'triage.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getRekonsiliasiObatAdmisi($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'rekonsiliasiObat.ID as id',
            ])
            ->leftJoin('medicalrecord.rekonsiliasi_obat as rekonsiliasiObat', 'rekonsiliasiObat.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getRekonsiliasiObatTransfer($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'rekonsiliasiTransfer.ID as id',
            ])
            ->leftJoin('medicalrecord.rekonsiliasi_transfer as rekonsiliasiTransfer', 'rekonsiliasiTransfer.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getRekonsiliasiObatDischarge($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'rekonsiliasiDischarge.ID as id',
            ])
            ->leftJoin('medicalrecord.rekonsiliasi_discharge as rekonsiliasiDischarge', 'rekonsiliasiDischarge.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getAskep($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'askep.ID as id',
            ])
            ->leftJoin('medicalrecord.asuhan_keperawatan as askep', 'askep.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getKeluhanUtama($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'keluhanUtama.ID as id',
            ])
            ->leftJoin('medicalrecord.keluhan_utama as keluhanUtama', 'keluhanUtama.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getAnamnesisDiperoleh($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'anamnesisDiperoleh.ID as id',
            ])
            ->leftJoin('medicalrecord.anamnesis_diperoleh as anamnesisDiperoleh', 'anamnesisDiperoleh.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getRiwayatPenyakitSekarang($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'anamnesis.ID as id',
            ])
            ->leftJoin('medicalrecord.anamnesis as anamnesis', 'anamnesis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getRiwayatPenyakitDahulu($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'rpp.ID as id',
            ])
            ->leftJoin('medicalrecord.rpp as rpp', 'rpp.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getRiwayatAlergi($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'riwayatAlergi.ID as id',
            ])
            ->leftJoin('medicalrecord.riwayat_alergi as riwayatAlergi', 'riwayatAlergi.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getRiwayatPemberianObat($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'riwayatPemberianObat.ID as id',
            ])
            ->leftJoin('medicalrecord.riwayat_pemberian_obat as riwayatPemberianObat', 'riwayatPemberianObat.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getRiwayatLainnya($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'riwayatLainnya.ID as id',
            ])
            ->leftJoin('medicalrecord.riwayat_lainnya as riwayatLainnya', 'riwayatLainnya.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getFaktorRisiko($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'faktorRisiko.ID as id',
            ])
            ->leftJoin('medicalrecord.faktor_risiko as faktorRisiko', 'faktorRisiko.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getRiwayatPenyakitKeluarga($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'rpk.ID as id',
            ])
            ->leftJoin('medicalrecord.riwayat_penyakit_keluarga as rpk', 'rpk.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getRiwayatTuberkulosis($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'riwayatTuberkulosis.ID as id',
            ])
            ->leftJoin('medicalrecord.riwayat_penyakit_tb as riwayatTuberkulosis', 'riwayatTuberkulosis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getRiwayatGinekologi($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'riwayatGynekologi.ID as id',
            ])
            ->leftJoin('medicalrecord.riwayat_gynekologi as riwayatGynekologi', 'riwayatGynekologi.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getStatusFungsional($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'statusFungsional.ID as id',
            ])
            ->leftJoin('medicalrecord.status_fungsional as statusFungsional', 'statusFungsional.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getHubunganPsikososial($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'kondisiSosial.ID as id',
            ])
            ->leftJoin('medicalrecord.kondisi_sosial as kondisiSosial', 'kondisiSosial.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getEdukasiPasienKeluarga($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'edukasiPasienKeluarga.ID as id',
            ])
            ->leftJoin('medicalrecord.edukasi_pasien_keluarga as edukasiPasienKeluarga', 'edukasiPasienKeluarga.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getEdukasiEmergency($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'edukasiEmergency.ID as id',
            ])
            ->leftJoin('medicalrecord.edukasi_emergency as edukasiEmergency', 'edukasiEmergency.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getEdukasiEndOfLife($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'edukasiEndOfLife.ID as id',
            ])
            ->leftJoin('medicalrecord.edukasi_end_of_life as edukasiEndOfLife', 'edukasiEndOfLife.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getSkriningGiziAwal($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'permasalahanGizi.ID as id',
            ])
            ->leftJoin('medicalrecord.permasalahan_gizi as permasalahanGizi', 'permasalahanGizi.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getBatuk($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'batuk.ID as id',
            ])
            ->leftJoin('medicalrecord.batuk as batuk', 'batuk.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanUmum($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'tandaVital.ID as id',
            ])
            ->leftJoin('medicalrecord.tanda_vital as tandaVital', 'tandaVital.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->where('tandaVital.KEADAAN_UMUM', '!=', '')
            ->first();
    }

    public function getPemeriksaanFungsional($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'fungsional.ID as id',
            ])
            ->leftJoin('medicalrecord.fungsional as fungsional', 'fungsional.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanFisik($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanFisik.ID as id',
            ])
            ->leftJoin('medicalrecord.penilaian_fisik as pemeriksaanFisik', 'pemeriksaanFisik.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanKepala($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanKepala.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_kepala as pemeriksaanKepala', 'pemeriksaanKepala.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanMata($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanMata.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_mata as pemeriksaanMata', 'pemeriksaanMata.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanTelinga($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanTelinga.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_telinga as pemeriksaanTelinga', 'pemeriksaanTelinga.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanHidung($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanHidung.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_hidung as pemeriksaanHidung', 'pemeriksaanHidung.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanRambut($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanRambut.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_rambut as pemeriksaanRambut', 'pemeriksaanRambut.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanBibir($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanBibir.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_bibir as pemeriksaanBibir', 'pemeriksaanBibir.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanGigiGeligi($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanGigiGeligi.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_gigi_geligi as pemeriksaanGigiGeligi', 'pemeriksaanGigiGeligi.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanLidah($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanLidah.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_lidah as pemeriksaanLidah', 'pemeriksaanLidah.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanLangitLangit($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanLangitLangit.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_langit_langit as pemeriksaanLangitLangit', 'pemeriksaanLangitLangit.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanLeher($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanLeher.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_leher as pemeriksaanLeher', 'pemeriksaanLeher.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanTenggorokan($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanTenggorokan.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_tenggorokan as pemeriksaanTenggorokan', 'pemeriksaanTenggorokan.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanTonsil($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanTonsil.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_tonsil as pemeriksaanTonsil', 'pemeriksaanTonsil.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanDada($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanDada.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_dada as pemeriksaanDada', 'pemeriksaanDada.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanPayudara($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanPayudara.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_payudara as pemeriksaanPayudara', 'pemeriksaanPayudara.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanPunggung($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanPunggung.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_punggung as pemeriksaanPunggung', 'pemeriksaanPunggung.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanPerut($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanPerut.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_perut as pemeriksaanPerut', 'pemeriksaanPerut.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanGenital($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanGenital.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_genital as pemeriksaanGenital', 'pemeriksaanGenital.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanAnus($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanAnus.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_anus as pemeriksaanAnus', 'pemeriksaanAnus.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanLenganAtas($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanLenganAtas.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_lengan_atas as pemeriksaanLenganAtas', 'pemeriksaanLenganAtas.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanLenganBawah($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanLenganBawah.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_lengan_bawah as pemeriksaanLenganBawah', 'pemeriksaanLenganBawah.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanJariTangan($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanJariTangan.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_jari_tangan as pemeriksaanJariTangan', 'pemeriksaanJariTangan.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanKukuTangan($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanKukuTangan.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_kuku_tangan as pemeriksaanKukuTangan', 'pemeriksaanKukuTangan.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanPersendianTangan($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanPersendianTangan.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_persendian_tangan as pemeriksaanPersendianTangan', 'pemeriksaanPersendianTangan.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanTungkaiAtas($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanTungkaiAtas.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_tungkai_atas as pemeriksaanTungkaiAtas', 'pemeriksaanTungkaiAtas.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanTungkaiBawah($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanTungkaiBawah.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_tungkai_bawah as pemeriksaanTungkaiBawah', 'pemeriksaanTungkaiBawah.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanJariKaki($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanJariKaki.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_jari_kaki as pemeriksaanJariKaki', 'pemeriksaanJariKaki.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanKukuKaki($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanKukuKaki.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_kuku_kaki as pemeriksaanKukuKaki', 'pemeriksaanKukuKaki.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanPersendianKaki($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanPersendianKaki.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_persendian_kaki as pemeriksaanPersendianKaki', 'pemeriksaanPersendianKaki.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanFaring($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanFaring.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_faring as pemeriksaanFaring', 'pemeriksaanFaring.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanSaluranCernahBawah($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanSaluranCernahBawah.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_saluran_cernah_bawah as pemeriksaanSaluranCernahBawah', 'pemeriksaanSaluranCernahBawah.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanSaluranCernahAtas($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanSaluranCernahAtas.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_saluran_cernah_atas as pemeriksaanSaluranCernahAtas', 'pemeriksaanSaluranCernahAtas.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanEeg($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanEeg.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_eeg as pemeriksaanEeg', 'pemeriksaanEeg.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanEmg($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanEmg.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_emg as pemeriksaanEmg', 'pemeriksaanEmg.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanRavenTest($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanRavenTest.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_raven_test as pemeriksaanRavenTest', 'pemeriksaanRavenTest.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanCatClams($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanCatClams.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_cat_clams as pemeriksaanCatClams', 'pemeriksaanCatClams.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanTransfusiDarah($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanTransfusiDarah.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_transfusi_darah as pemeriksaanTransfusiDarah', 'pemeriksaanTransfusiDarah.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanAsessmentMChat($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanAsessmentMChat.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_assesment_m_chat as pemeriksaanAsessmentMChat', 'pemeriksaanAsessmentMChat.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemeriksaanEkg($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemeriksaanEkg.ID as id',
            ])
            ->leftJoin('medicalrecord.pemeriksaan_ekg as pemeriksaanEkg', 'pemeriksaanEkg.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPenilaianFisik($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'penilaianFisik.ID as id',
            ])
            ->leftJoin('medicalrecord.penilaian_fisik as penilaianFisik', 'penilaianFisik.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPenilaianNyeri($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'penilaianNyeri.ID as id',
            ])
            ->leftJoin('medicalrecord.penilaian_nyeri as penilaianNyeri', 'penilaianNyeri.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPenilaianStatusPediatrik($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'penilaianStatusPediatrik.ID as id',
            ])
            ->leftJoin('medicalrecord.status_pediatric as penilaianStatusPediatrik', 'penilaianStatusPediatrik.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPenilaianDiagnosis($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'penilaianDiagnosis.ID as id',
            ])
            ->leftJoin('medicalrecord.diagnosis as penilaianDiagnosis', 'penilaianDiagnosis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPenilaianSkalaMorse($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'penilaianSkalaMorse.ID as id',
            ])
            ->leftJoin('medicalrecord.penilaian_skala_morse as penilaianSkalaMorse', 'penilaianSkalaMorse.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPenilaianSkalaHumptyDumpty($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'penilaianSkalaHumptyDumpty.ID as id',
            ])
            ->leftJoin('medicalrecord.penilaian_skala_humpty_dumpty as penilaianSkalaHumptyDumpty', 'penilaianSkalaHumptyDumpty.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPenilaianEpfra($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'penilaianEpfra.ID as id',
            ])
            ->leftJoin('medicalrecord.penilaian_epfra as penilaianEpfra', 'penilaianEpfra.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPenilaianGetupGo($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'penilaianGetupGo.ID as id',
            ])
            ->leftJoin('medicalrecord.penilaian_getup_and_go as penilaianGetupGo', 'penilaianGetupGo.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPenilaianDekubitus($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'penilaianDekubitus.ID as id',
            ])
            ->leftJoin('medicalrecord.penilaian_dekubitus as penilaianDekubitus', 'penilaianDekubitus.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPenilaianBallanceCairan($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'penilaianBallanceCairan.ID as id',
            ])
            ->leftJoin('medicalrecord.penilaian_ballance_cairan as penilaianBallanceCairan', 'penilaianBallanceCairan.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getRencanaTerapi($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'rencanaTerapi.ID as id',
            ])
            ->leftJoin('medicalrecord.rencana_terapi as rencanaTerapi', 'rencanaTerapi.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getJadwalKontrol($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'jadwalKontrol.ID as id',
            ])
            ->leftJoin('medicalrecord.jadwal_kontrol as jadwalKontrol', 'jadwalKontrol.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPerencanaanRawatInap($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'perencanaanRawatInap.ID as id',
            ])
            ->leftJoin('medicalrecord.perencanaan_rawat_inap as perencanaanRawatInap', 'perencanaanRawatInap.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getDischargePlanningSkrining($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'dischargePlanningSkrining.ID as id',
            ])
            ->leftJoin('medicalrecord.discharge_planning_skrining as dischargePlanningSkrining', 'dischargePlanningSkrining.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getDischargePlanningFaktorRisiko($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'dischargePlanningFaktorRisiko.ID as id',
            ])
            ->leftJoin('medicalrecord.discharge_planning_faktor_risiko as dischargePlanningFaktorRisiko', 'dischargePlanningFaktorRisiko.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getCppt($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'cppt.KUNJUNGAN as id',
            ])
            ->leftJoin('medicalrecord.cppt as cppt', 'cppt.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getPemantauanHDIntradialitik($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'pemantuanHDIntradialitik.KUNJUNGAN as id',
            ])
            ->leftJoin('medicalrecord.pemantuan_hd_intradialitik as pemantuanHDIntradialitik', 'pemantuanHDIntradialitik.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getTindakanAbci($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'tindakanAbci.KUNJUNGAN as id',
            ])
            ->leftJoin('medicalrecord.tindakan_abci as tindakanAbci', 'tindakanAbci.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function getTindakanMmpi($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'tindakanMmpi.KUNJUNGAN as id',
            ])
            ->leftJoin('medicalrecord.tindakan_mmpi as tindakanMmpi', 'tindakanMmpi.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }
}