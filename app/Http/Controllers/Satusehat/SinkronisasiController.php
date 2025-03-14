<?php

namespace App\Http\Controllers\Satusehat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SinkronisasiController extends Controller
{
    public function index()
    {
        //get data satusehat
        $query = "
            SELECT
                'Organization' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 1) LAST_UPDATE
            FROM
                `kemkes-ihs`.`organization` o
            UNION ALL
            SELECT
                'Location' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 1) LAST_UPDATE
            FROM
                `kemkes-ihs`.`location` o
            UNION ALL
            SELECT
                'Patient' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 2) LAST_UPDATE
            FROM
                `kemkes-ihs`.`patient` o
            UNION ALL
            SELECT
                'Practitioner' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 3) LAST_UPDATE
            FROM
                `kemkes-ihs`.`practitioner` o
            UNION ALL
            SELECT
                'Encounter' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 4) LAST_UPDATE
            FROM
                `kemkes-ihs`.`encounter` o
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
            UNION ALL
            SELECT
                'Condition' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 5) LAST_UPDATE
            FROM
                `kemkes-ihs`.`condition` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.NOPEN AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.nopen=pp.NOMOR
            UNION ALL
            SELECT
                'Observation' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 6) LAST_UPDATE
            FROM
                `kemkes-ihs`.`observation` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.NOPEN AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.nopen=pp.NOMOR
            UNION ALL
            SELECT
                'Procedure' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 7) LAST_UPDATE
            FROM
                `kemkes-ihs`.`procedure` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.NOPEN AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.nopen=pp.NOMOR
            UNION ALL
            SELECT
                'Composition' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 11) LAST_UPDATE
            FROM
                `kemkes-ihs`.`composition` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.nopen AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.nopen=pp.NOMOR
            UNION ALL
            SELECT
                'Medication' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 10) LAST_UPDATE
            FROM
                `kemkes-ihs`.`medication` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.nopen AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.nopen=pp.NOMOR
            UNION ALL
            SELECT
                'Medication Request' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 9) LAST_UPDATE
            FROM
                `kemkes-ihs`.`medication_request` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.nopen AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.nopen=pp.NOMOR
            UNION ALL
            SELECT
                'Medication Dispanse' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 10) LAST_UPDATE
            FROM
                `kemkes-ihs`.`medication_dispanse` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.nopen AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.nopen=pp.NOMOR
            UNION ALL
            SELECT
                'Service Request' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 12) LAST_UPDATE
            FROM
                `kemkes-ihs`.`service_request` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.nopen AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.nopen=pp.NOMOR
            UNION ALL
            SELECT
                'Specimen' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 12) LAST_UPDATE
            FROM
                `kemkes-ihs`.`specimen` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.nopen AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.nopen=pp.NOMOR
            UNION ALL
            SELECT
                'Diagnostic Report' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 5) LAST_UPDATE
            FROM
                `kemkes-ihs`.`diagnostic_report` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.nopen AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.nopen=pp.NOMOR
            UNION ALL
            SELECT
                'Consent' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 13) LAST_UPDATE
            FROM
                `kemkes-ihs`.`consent` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
            UNION ALL
            SELECT
                'Allergy Intolerance' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 16) LAST_UPDATE
            FROM
                `kemkes-ihs`.`allergy_intolerance` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
            UNION ALL
            SELECT
                'Care Plan' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 14) LAST_UPDATE
            FROM
                `kemkes-ihs`.`care_plan` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
            UNION ALL
            SELECT
                'Condition Hasil PA' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 17) LAST_UPDATE
            FROM
                `kemkes-ihs`.`condition_hasil_pa` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
            UNION ALL
            SELECT
                'Condition Penilaian Tumor' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 17) LAST_UPDATE
            FROM
                `kemkes-ihs`.`condition_penilaian_tumor` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
            UNION ALL
            SELECT
                'Condition Riwayat Penyakit Dahulu' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 25) LAST_UPDATE
            FROM
                `kemkes-ihs`.`condition_riwayat_penyakit_dahulu` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
            UNION ALL
            SELECT
                'Medication Statement' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 9) LAST_UPDATE
            FROM
                `kemkes-ihs`.`medication_statement` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
            UNION ALL
            SELECT
                'Observation Anamnesis Riwayat Lainnya' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 20) LAST_UPDATE
            FROM
                `kemkes-ihs`.`observation_anamnesis_riwayat_lainnya` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
            UNION ALL
            SELECT
                'Observation Faktor Risiko' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 26) LAST_UPDATE
            FROM
                `kemkes-ihs`.`observation_faktor_risiko` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
            UNION ALL
            SELECT
                'Observation Pemeriksaan EKG' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 21) LAST_UPDATE
            FROM
                `kemkes-ihs`.`observation_pemeriksaan_ekg` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
            UNION ALL
            SELECT
                'Observation Penilaian Grace Risk Skor' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 22) LAST_UPDATE
            FROM
                `kemkes-ihs`.`observation_penilaian_grace_risk_skor` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
            UNION ALL
            SELECT
                'Service Request Jadwal Kontrol' NAMA,
                IFNULL(SUM(IF(o.id IS NOT NULL, 1, 0)), 0) MEMILIKI_ID,
                IFNULL(SUM(IF(o.id IS NULL, 1, 0)), 0) TIDAK_MEMILIKI_ID,
                COUNT(*) TOTAL,
                IFNULL((SUM(IF(o.id IS NOT NULL, 1, 0)) / COUNT(*)) * 100, 0) AS PERSEN,
                (SELECT TANGGAL_TERAKHIR FROM `kemkes-ihs`.`sinkronisasi` WHERE ID = 28) LAST_UPDATE
            FROM
                `kemkes-ihs`.`service_request_jadwal_kontrol` o
                LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN=o.refId AND tp.`STATUS` !=0
                LEFT JOIN `master`.ruangan r ON r.ID=tp.RUANGAN
                LEFT JOIN pendaftaran.pendaftaran pp ON o.refId=pp.NOMOR
            ";

        // Execute the query using the mysql4 connection
        $data = DB::connection('mysql4')->select($query);

        // Return Inertia view with paginated data
        return inertia("Satusehat/Sinkronisasi/Index", [
            'items' => $data,
        ]);
    }
}