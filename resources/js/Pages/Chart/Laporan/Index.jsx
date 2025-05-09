import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import Indikator from './Indikator';
import Grouping from './Grouping';
import PasienMasukKeluar from './PasienMasukKeluar';
import PasienRanap from './PasienRanap';
import LaporanRl32 from './LaporanRl32';

export default function Index({
    auth,
    tahunIni,
    tahunLalu,
    pasienBelumGrouping,
    pasienBelumGroupingLalu,
    indikatorPelayanan,
    pasienMasukKeluar,
    pasienRanap,
    laporanRl32,
    laporanRl32Lalu,
}) {

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Chart" />

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <Indikator
                        indikatorPelayanan={indikatorPelayanan}
                    />
                </div>
                <div className="w-1/2">
                    <PasienMasukKeluar
                        pasienMasukKeluar={pasienMasukKeluar}
                    />
                </div>
            </div>

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <PasienRanap
                        pasienRanap={pasienRanap}
                    />
                </div>
                <div className="w-1/2">
                    <LaporanRl32
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        laporanRl32={laporanRl32}
                        laporanRl32Lalu={laporanRl32Lalu}
                    />
                </div>
            </div>

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <PasienRanap
                        pasienRanap={pasienRanap}
                    />
                </div>
                <div className="w-1/2">
                    <Grouping
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        pasienBelumGrouping={pasienBelumGrouping}
                        pasienBelumGroupingLalu={pasienBelumGroupingLalu}
                    />
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
