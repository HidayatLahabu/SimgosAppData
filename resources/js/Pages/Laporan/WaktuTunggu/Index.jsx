import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import Harian from './Harian';
import Ratarata from './Ratarata';

export default function Index({ auth, dataTable, tgl_awal, tgl_akhir, queryParams = {}, averageWaitData, currentMonth, currentYear }) {


    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Laporan" />

            <Harian
                dataTable={dataTable}
                queryParams={queryParams}
                tgl_awal={tgl_awal}
                tgl_akhir={tgl_akhir}
            />

            <Ratarata
                averageWaitData={averageWaitData}
                queryParams={queryParams}
                currentMonth={currentMonth}
                currentYear={currentYear}
            />

        </AuthenticatedLayout>
    );
}
