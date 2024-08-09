<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with data.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Define the SQL query as a plain string
        $query = "
            SELECT 'Organization' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`organization` p
            UNION
            SELECT 'Location' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`location` p
            UNION
            SELECT 'Patient' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`patient` p
            UNION
            SELECT 'Practitioner' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`practitioner` p
            UNION
            SELECT 'Encounter' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`encounter` p
            UNION
            SELECT 'Condition' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`condition` p
            UNION
            SELECT 'Observation' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`observation` p
            UNION
            SELECT 'Procedure' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`procedure` p
            UNION
            SELECT 'Composition' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`composition` p
            UNION
            SELECT 'Consent' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`consent` p
            UNION
            SELECT 'Diagnostic Report' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`diagnostic_report` p
            UNION
            SELECT 'Medication' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`medication` p
            UNION
            SELECT 'Medication Dispanse' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`medication_dispanse` p
            UNION
            SELECT 'Medication Request' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`medication_request` p
            UNION
            SELECT 'Service Request' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`service_request` p
            UNION
            SELECT 'Specimen' AS NAMA,
                IFNULL(SUM(IF(p.id IS NULL, 0, 1)), 0) AS MEMILIKI_ID,
                IFNULL(SUM(IF(p.id IS NULL, 1, 0)), 0) AS TDK_MEMILIKI_ID,
                IFNULL(COUNT(*), 0) AS TOTAL
            FROM `kemkes-ihs`.`specimen` p
        ";

        // Execute the query using the mysql4 connection
        $data = DB::connection('mysql4')->select($query);

        //dd($data);
        // Pass the data to the Inertia view
        return Inertia::render('Dashboard', [
            'items' => $data
        ]);
    }
}