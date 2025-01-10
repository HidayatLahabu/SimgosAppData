import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import KunjunganBulanan from './KunjunganBulanan';
import RujukanBulanan from './RujukanBulanan';
import Kunjungan from './Kunjungan';
import Rujukan from './Rujukan';
import KunjunganMingguan from './KunjunganMingguan';
import RujukanMingguan from './RujukanMingguan';
import KunjunganTahunan from './KunjunganTahunan';
import RujukanTahunan from './RujukanTahunan';

export default function Index({
    auth,
    kunjungan,
    rujukan,
    kunjunganMingguan,
    rujukanMingguan,
    kunjunganBulanan,
    rujukanBulanan,
    kunjunganTahunan,
    rujukanTahunan
}) {

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Informasi" />

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <Kunjungan kunjungan={kunjungan} />
                </div>
                <div className="w-1/2 pl-2">
                    <Rujukan rujukan={rujukan} />
                </div>
            </div>

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <KunjunganMingguan kunjunganMingguan={kunjunganMingguan} />
                </div>
                <div className="w-1/2 pl-2">
                    <RujukanMingguan rujukanMingguan={rujukanMingguan} />
                </div>
            </div>

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <KunjunganBulanan kunjunganBulanan={kunjunganBulanan} />
                </div>
                <div className="w-1/2 pl-2">
                    <RujukanBulanan rujukanBulanan={rujukanBulanan} />
                </div>
            </div>

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <KunjunganTahunan kunjunganTahunan={kunjunganTahunan} />
                </div>
                <div className="w-1/2 pl-2">
                    <RujukanTahunan rujukanTahunan={rujukanTahunan} />
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
