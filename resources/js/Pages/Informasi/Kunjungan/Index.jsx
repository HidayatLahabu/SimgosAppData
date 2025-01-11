import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import Harian from './Harian';
import Mingguan from './Mingguan';
import Bulanan from './Bulanan';
import Tahunan from './Tahunan';
import Cetak from './Cetak';

export default function Index({
    auth,
    harian,
    mingguan,
    bulanan,
    tahunan,
    ruangan,
}) {

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Informasi" />

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <Harian harian={harian} />
                </div>
                <div className="w-1/2">
                    <Mingguan mingguan={mingguan} />
                </div>
            </div>

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <Bulanan bulanan={bulanan} />
                </div>
                <div className="w-1/2">
                    <Tahunan tahunan={tahunan} />
                </div>
            </div>
            <div className="w-full">
                <Cetak
                    ruangan={ruangan}
                />
            </div>
        </AuthenticatedLayout>
    );
}
