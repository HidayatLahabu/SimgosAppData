import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import Harian from './Harian';
import Mingguan from './Mingguan';
import Bulanan from './Bulanan';

export default function Index({ auth, harian, queryParams, mingguan, bulanan }) {

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Informasi" />

            <Harian harian={harian} queryParams={queryParams} />

            <Mingguan mingguan={mingguan} />

            <Bulanan bulanan={bulanan} />

        </AuthenticatedLayout>
    );
}
