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
}