import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import Kunjungan from './Kunjungan';
import Peserta from './Peserta';
import Rekon from './Rekon';
import Monitoring from './Monitoring';
import Batal from './Batal';
import Kontrol from './Kontrol';

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
    batalTahunIni,
    batalTahunLalu,
    kontrolTahunIni,
    kontrolTahunLalu,
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

            <div className="flex flex-wrap w-full h-full items-stretch">
                <div className="w-1/3 flex">
                    <Rekon
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        rekonTahunIni={rekonTahunIni}
                        rekonTahunLalu={rekonTahunLalu}
                    />
                </div>
                <div className="w-1/3 flex">
                    <Monitoring
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        monitoringTahunIni={monitoringTahunIni}
                        monitoringTahunLalu={monitoringTahunLalu}
                    />
                </div>
                <div className="w-1/3 flex">
                    <Batal
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        batalTahunIni={batalTahunIni}
                        batalTahunLalu={batalTahunLalu}
                    />
                </div>
            </div>

            <div className="flex flex-col w-full">
                <Kontrol
                    tahunIni={tahunIni}
                    tahunLalu={tahunLalu}
                    rekonTahunIni={kontrolTahunIni.rekon}
                    monitoringTahunIni={kontrolTahunIni.monitoring}
                    batalTahunIni={kontrolTahunIni.batal}
                    rekonTahunLalu={kontrolTahunLalu.rekon}
                    monitoringTahunLalu={kontrolTahunLalu.monitoring}
                    batalTahunLalu={kontrolTahunLalu.batal}
                />
            </div>
        </AuthenticatedLayout>
    );
}
