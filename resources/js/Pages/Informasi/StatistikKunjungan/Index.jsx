import React, { useState } from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import KunjunganBulanan from './KunjunganBulanan';
import RujukanBulanan from './RujukanBulanan';
import KunjunganTable from './Kunjungan';
import RujukanTable from './Rujukan';

export default function Index({ auth, tableKunjungan, tableRujukan, kunjunganBulanan, rujukanBulanan }) {

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Medicalrecord" />

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2">
                    <KunjunganTable tableKunjungan={tableKunjungan} />
                </div>
                <div className="w-1/2">
                    <RujukanTable tableRujukan={tableRujukan} />
                </div>
            </div>

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2">
                    <KunjunganBulanan kunjunganBulanan={kunjunganBulanan} />
                </div>
                <div className="w-1/2">
                    <RujukanBulanan rujukanBulanan={rujukanBulanan} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
