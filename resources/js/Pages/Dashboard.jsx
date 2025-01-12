import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import TodayData from './Dashboard/TodayData';
import StatisticTahun from './Dashboard/StatisticTahun';
import StatisticBulan from './Dashboard/StatisticBulan';
import KunjunganHarian from './Dashboard/KunjunganHarian';
import RajalBulanan from './Dashboard/RajalBulanan'
import DaruratBulanan from './Dashboard/DaruratBulanan'
import RanapBulanan from './Dashboard/RanapBulanan'
import LaboratoriumBulanan from './Dashboard/LaboratoriumBulanan';
import RadiologiBulanan from './Dashboard/RadiologiBulanan';

export default function Dashboard({
    auth,
    pendaftaran,
    kunjungan,
    konsul,
    mutasi,
    kunjunganBpjs,
    laboratorium,
    radiologi,
    resep,
    pulang,
    statistikKunjungan,
    statistikTahunIni,
    statistikTahunLalu,
    statistikBulanIni,
    statistikBulanLalu,
    rawatJalanBulanan,
    rawatDaruratBulanan,
    rawatInapBulanan,
    laboratoriumBulanan,
    radiologiBulanan,
}) {

    const today = new Date();

    const formattedDate = today.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }) + ', JAM ' + today.toLocaleTimeString('id-ID', {
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hour12: false,
    }).replace(/\./g, ':');

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Beranda" />

            <div className="max-w-full mx-auto sm:px-5 lg:px- w-full pt-3">
                <h1 className="uppercase text-center font-extrabold text-lg pb-2 text-indigo-700 dark:text-yellow-400">
                    {formattedDate}
                </h1>
                <h1 className="uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400">
                    Input Data
                </h1>
            </div>

            <div className="pt-3 pb-2 flex flex-wrap w-full">
                <TodayData
                    auth={auth}
                    pendaftaran={pendaftaran}
                    kunjungan={kunjungan}
                    konsul={konsul}
                    mutasi={mutasi}
                    kunjunganBpjs={kunjunganBpjs}
                    laboratorium={laboratorium}
                    radiologi={radiologi}
                    resep={resep}
                    pulang={pulang}
                />
            </div>

            <div className="pb-2 flex flex-wrap w-full">
                <KunjunganHarian statistikKunjungan={statistikKunjungan} />
            </div>

            <div className="flex flex-row gap-7 justify-center items-start w-full">
                {/* Data Tahun Ini */}
                <div className="flex flex-col w-1/2 -mx-1 pl-1">
                    <h1 className="pt-3 uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400 mb-2">
                        Data Tahun Ini
                    </h1>
                    <div className="pb-2 flex flex-wrap w-full">
                        <StatisticTahun
                            statistikTahunIni={statistikTahunIni}
                            statistikTahunLalu={statistikTahunLalu}
                        />
                    </div>
                </div>

                {/* Data Bulan Ini */}
                <div className="flex flex-col w-1/2 -mx-1">
                    <h1 className="pt-3 uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400 mb-2">
                        Data Bulan Ini
                    </h1>
                    <div className="pb-2 flex flex-wrap w-full pr-1">
                        <StatisticBulan
                            statistikBulanIni={statistikBulanIni}
                            statistikBulanLalu={statistikBulanLalu}
                        />
                    </div>
                </div>
            </div>




            <h1 className="uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400 mb-2">
                Kunjungan Bulanan
            </h1>
            <div className="pb-5 flex flex-wrap w-full">
                <div className="w-1/5">
                    <RajalBulanan rawatJalanBulanan={rawatJalanBulanan} />
                </div>
                <div className="w-1/5">
                    <DaruratBulanan rawatDaruratBulanan={rawatDaruratBulanan} />
                </div>
                <div className="w-1/5">
                    <RanapBulanan rawatInapBulanan={rawatInapBulanan} />
                </div>
                <div className="w-1/5">
                    <LaboratoriumBulanan laboratoriumBulanan={laboratoriumBulanan} />
                </div>
                <div className="w-1/5">
                    <RadiologiBulanan radiologiBulanan={radiologiBulanan} />
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
