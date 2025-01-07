import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import Indikator from './Indikator';
import IndikatorTriwulan from './IndikatorTriwulan';
import IndikatorSemester from './IndikatorSemester';
import Bulanan from './Bulanan';

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
    statistikTriwulanIni,
    statistikSemesterIni,
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
    statistikTriwulanLalu,
    statistikSemesterLalu,
    statistikBulanan,
}) {

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="max-w-full mx-auto w-full pt-5 text-yellow-500">
                <h1 className="uppercase text-center font-bold text-2xl">LAPORAN RL 1.2</h1>
                <p className="text-center font-bold">Sumber : Database Informasi</p>
            </div>

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

            <Bulanan
                statistikBulanan={statistikBulanan}
            />

            <IndikatorTriwulan
                tahunIni={tahunIni}
                statistikTriwulanIni={statistikTriwulanIni}
                tahunLalu={tahunLalu}
                statistikTriwulanLalu={statistikTriwulanLalu}
            />

            <IndikatorSemester
                tahunIni={tahunIni}
                statistikSemesterIni={statistikSemesterIni}
                tahunLalu={tahunLalu}
                statistikSemesterLalu={statistikSemesterLalu}
            />



        </AuthenticatedLayout>
    );
}
