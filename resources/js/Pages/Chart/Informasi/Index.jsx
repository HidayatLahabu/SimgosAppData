import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import KunjunganHarian from './KunjunganHarian';
import RujukanHarian from './RujukanHarian';
import KunjunganMingguan from './KunjunganMingguan';
import RujukanMingguan from './RujukanMingguan';
import KunjunganBulanan from './KunjunganBulanan';
import RujukanBulanan from './RujukanBulanan';
import KunjunganTahunan from './KunjunganTahunan';
import RujukanTahunan from './RujukanTahunan';

export default function Index({
    auth,
    kunjunganHarian,
    rujukanHarian,
    kunjunganMingguan,
    rujukanMingguan,
    kunjunganBulanan,
    rujukanBulanan,
    kunjunganTahunan,
    rujukanTahunan,
}) {

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Chart" />

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <KunjunganHarian
                        kunjunganHarian={kunjunganHarian}
                    />
                </div>
                <div className="w-1/2">
                    <RujukanHarian
                        rujukanHarian={rujukanHarian}
                    />
                </div>
            </div>

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <KunjunganMingguan
                        kunjunganMingguan={kunjunganMingguan}
                    />
                </div>
                <div className="w-1/2">
                    <RujukanMingguan
                        rujukanMingguan={rujukanMingguan}
                    />
                </div>
            </div>

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <KunjunganBulanan
                        kunjunganBulanan={kunjunganBulanan}
                    />
                </div>
                <div className="w-1/2">
                    <RujukanBulanan
                        rujukanBulanan={rujukanBulanan}
                    />
                </div>
            </div>

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <KunjunganTahunan
                        kunjunganTahunan={kunjunganTahunan}
                    />
                </div>
                <div className="w-1/2">
                    <RujukanTahunan
                        rujukanTahunan={rujukanTahunan}
                    />
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
