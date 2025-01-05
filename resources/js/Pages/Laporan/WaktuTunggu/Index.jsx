import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import Harian from './Harian';
import Ratarata from './Ratarata';

export default function Index({ auth, dataTable, queryParams = {}, averageWaitData }) {


    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Laporan" />

            <Harian
                dataTable={dataTable}
                queryParams={queryParams}
            />

            <Ratarata
                averageWaitData={averageWaitData}
                queryParams={queryParams}
            />

        </AuthenticatedLayout>
    );
}
