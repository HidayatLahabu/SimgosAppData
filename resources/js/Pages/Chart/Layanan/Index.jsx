import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import Laboratorium from './Laboratorium';
import Radiologi from './Radiologi';
import HasilLab from './HasilLab';
import HasilRad from './HasilRad';

export default function Index({
    auth,
    tahunIni,
    tahunLalu,
    laboratoriumTahunIni,
    laboratoriumTahunLalu,
    radiologiTahunIni,
    radiologiTahunLalu,
    hasilLabTahunIni,
    hasilLabTahunLalu,
    hasilRadTahunIni,
    hasilRadTahunLalu,
}) {

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Chart" />

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <Laboratorium
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        laboratoriumTahunIni={laboratoriumTahunIni}
                        laboratoriumTahunLalu={laboratoriumTahunLalu}
                    />
                </div>
                <div className="w-1/2">
                    <Radiologi
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        radiologiTahunIni={radiologiTahunIni}
                        radiologiTahunLalu={radiologiTahunLalu}
                    />
                </div>
            </div>

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <HasilLab
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        hasilLabTahunIni={hasilLabTahunIni}
                        hasilLabTahunLalu={hasilLabTahunLalu}
                    />
                </div>
                <div className="w-1/2">
                    <HasilRad
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        hasilRadTahunIni={hasilRadTahunIni}
                        hasilRadTahunLalu={hasilRadTahunLalu}
                    />
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
