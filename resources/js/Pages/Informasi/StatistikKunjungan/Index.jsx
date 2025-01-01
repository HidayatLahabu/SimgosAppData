import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import KunjunganBulanan from './KunjunganBulanan';
import RujukanBulanan from './RujukanBulanan';
import KunjunganTable from './Kunjungan';
import RujukanTable from './Rujukan';
import KunjunganMingguanTable from './KunjunganMingguan';
import RujukanMingguanTable from './RujukanMingguan';

export default function Index({
    auth,
    tableKunjungan,
    tableRujukan,
    kunjunganMingguan,
    rujukanMingguan,
    kunjunganBulanan,
    rujukanBulanan
}) {

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Informasi" />

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <KunjunganTable tableKunjungan={tableKunjungan} />
                </div>
                <div className="w-1/2 pl-2">
                    <RujukanTable tableRujukan={tableRujukan} />
                </div>
            </div>

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <KunjunganMingguanTable kunjunganMingguan={kunjunganMingguan} />
                </div>
                <div className="w-1/2 pl-2">
                    <RujukanMingguanTable rujukanMingguan={rujukanMingguan} />
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
        </AuthenticatedLayout>
    );
}
