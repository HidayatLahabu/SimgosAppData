import React, { useState } from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import RajalTable from './Rajal';
import RanapTable from './Ranap';
import RajalMingguanTable from './RajalMingguan';
import RanapMingguanTable from './RanapMingguan';
import RajalBulananTable from './RajalBulanan';
import RanapBulananTable from './RanapBulanan';

export default function Index({ auth, tablePengunjung, tableRanap, rajalMingguan, ranapMingguan, rajalBulanan, ranapBulanan }) {

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Informasi" />

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <RajalTable tablePengunjung={tablePengunjung} />
                </div>
                <div className="w-1/2 pl-2">
                    <RanapTable tableRanap={tableRanap} />
                </div>
            </div>

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <RajalMingguanTable rajalMingguan={rajalMingguan} />
                </div>
                <div className="w-1/2 pl-2">
                    <RanapMingguanTable ranapMingguan={ranapMingguan} />
                </div>
            </div>

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <RajalBulananTable rajalBulanan={rajalBulanan} />
                </div>
                <div className="w-1/2 pl-2">
                    <RanapBulananTable ranapBulanan={ranapBulanan} />
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
