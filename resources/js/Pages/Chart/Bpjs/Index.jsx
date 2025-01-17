import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import Kunjungan from './Kunjungan';
import Peserta from './Peserta';
import Rekon from './Rekon';
import Monitoring from './Monitoring';

export default function Index({
    auth,
    tahunIni,
    tahunLalu,
    bpjsTahunIni,
    bpjsTahunLalu,
    kunjunganTahunIni,
    kunjunganTahunLalu,
    rekonTahunIni,
    rekonTahunLalu,
    monitoringTahunIni,
    monitoringTahunLalu,
}) {

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Chart" />

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <Peserta
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        bpjsTahunIni={bpjsTahunIni}
                        bpjsTahunLalu={bpjsTahunLalu}
                    />
                </div>
                <div className="w-1/2">
                    <Kunjungan
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        kunjunganTahunIni={kunjunganTahunIni}
                        kunjunganTahunLalu={kunjunganTahunLalu}
                    />
                </div>
            </div>

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <Rekon
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        rekonTahunIni={rekonTahunIni}
                        rekonTahunLalu={rekonTahunLalu}
                    />
                </div>
                <div className="w-1/2">
                    <Monitoring
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        monitoringTahunIni={monitoringTahunIni}
                        monitoringTahunLalu={monitoringTahunLalu}
                    />
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
