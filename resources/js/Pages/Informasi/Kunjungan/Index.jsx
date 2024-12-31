import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import Kunjungan from './Kunjungan';
import KunjunganMingguan from './KunjunganMingguan';
import KunjunganBulanan from './KunjunganBulanan';

export default function Index({ auth, dataTable, queryParams, kunjunganMingguan, kunjunganBulanan }) {

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Informasi" />

            <Kunjungan dataTable={dataTable} queryParams={queryParams} />

            <KunjunganMingguan kunjunganMingguan={kunjunganMingguan} />

            <KunjunganBulanan kunjunganBulanan={kunjunganBulanan} />

        </AuthenticatedLayout>
    );
}
