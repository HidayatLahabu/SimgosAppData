import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { formatDate } from '@/utils/formatDate';
import { formatNumber } from '@/utils/formatNumber';
import ThisYear from './ThisYear';
import LastYear from './LastYear';

export default function LaporanRl12({ auth, items_current_year, tgl_awal, tgl_akhir, items_last_year, tgl_awal_last_year, tgl_akhir_last_year }) {

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="flex">
                <ThisYear
                    items_current_year={items_current_year}
                    tgl_awal={tgl_awal}
                    tgl_akhir={tgl_akhir}
                />
                <LastYear
                    items_last_year={items_last_year}
                    tgl_awal_last_year={tgl_awal_last_year}
                    tgl_akhir_last_year={tgl_akhir_last_year}
                />
            </div>


        </AuthenticatedLayout>
    );
}
