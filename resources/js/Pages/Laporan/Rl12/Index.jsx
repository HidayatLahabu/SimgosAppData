import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import Indikator from './Indikator';
import Cetak from './Cetak';

export default function LaporanRl12({ auth,
    //tahun ini
    tahunIni,
    ttidurIni,
    awalTahunIni,
    masukTahunIni,
    keluarTahunIni,
    lebih48TahunIni,
    kurang48TahunIni,
    sisaTahunIni,
    jumlahKunjunganTahunIni,
    statistikTahunIni,
    //tahun lalu
    tahunLalu,
    ttidurLalu,
    awalTahunLalu,
    masukTahunLalu,
    keluarTahunLalu,
    lebih48TahunLalu,
    kurang48TahunLalu,
    sisaTahunLalu,
    jumlahKunjunganTahunLalu,
    statistikTahunLalu,
}) {

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <Indikator
                tahunIni={tahunIni}
                ttidurIni={ttidurIni}
                awalTahunIni={awalTahunIni}
                masukTahunIni={masukTahunIni}
                keluarTahunIni={keluarTahunIni}
                lebih48TahunIni={lebih48TahunIni}
                kurang48TahunIni={kurang48TahunIni}
                sisaTahunIni={sisaTahunIni}
                jumlahKunjunganTahunIni={jumlahKunjunganTahunIni}
                statistikTahunIni={statistikTahunIni}
                //tahun lalu
                tahunLalu={tahunLalu}
                ttidurLalu={ttidurLalu}
                awalTahunLalu={awalTahunLalu}
                masukTahunLalu={masukTahunLalu}
                keluarTahunLalu={keluarTahunLalu}
                lebih48TahunLalu={lebih48TahunLalu}
                kurang48TahunLalu={kurang48TahunLalu}
                sisaTahunLalu={sisaTahunLalu}
                jumlahKunjunganTahunLalu={jumlahKunjunganTahunLalu}
                statistikTahunLalu={statistikTahunLalu}
            />

            <div className="w-full">
                <Cetak
                />
            </div>

        </AuthenticatedLayout>
    );
}
