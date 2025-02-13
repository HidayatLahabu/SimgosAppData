import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import Kunjungan from './Kunjungan';
import Pendaftaran from './Pendaftaran';
import Konsul from './Konsul';
import Mutasi from './Mutasi';
import Antrian from './Antrian';
import Reservasi from './Reservasi';

export default function Index({
    auth,
    tahunIni,
    tahunLalu,
    pendaftaranTahunIni,
    pendaftaranTahunLalu,
    kunjunganTahunIni,
    kunjunganTahunLalu,
    konsulTahunIni,
    konsulTahunLalu,
    mutasiTahunIni,
    mutasiTahunLalu,
    antrianTahunIni,
    antrianTahunLalu,
    reservasiTahunIni,
    reservasiTahunLalu,
}) {

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Chart" />

            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <Pendaftaran
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        pendaftaranTahunIni={pendaftaranTahunIni}
                        pendaftaranTahunLalu={pendaftaranTahunLalu}
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
                    <Konsul
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        konsulTahunIni={konsulTahunIni}
                        konsulTahunLalu={konsulTahunLalu}
                    />
                </div>
                <div className="w-1/2">
                    <Mutasi
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        mutasiTahunIni={mutasiTahunIni}
                        mutasiTahunLalu={mutasiTahunLalu}
                    />
                </div>
            </div>
            <div className="flex flex-wrap w-full">
                <div className="w-1/2">
                    <Antrian
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        antrianTahunIni={antrianTahunIni}
                        antrianTahunLalu={antrianTahunLalu}
                    />
                </div>
                <div className="w-1/2">
                    <Reservasi
                        tahunIni={tahunIni}
                        tahunLalu={tahunLalu}
                        reservasiTahunIni={reservasiTahunIni}
                        reservasiTahunLalu={reservasiTahunLalu}
                    />
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
