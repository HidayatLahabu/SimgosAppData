import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import TodayData from './Dashboard/TodayData';
import MonthlyPendaftaranTable from './Dashboard/PendaftaranBulanan';
import MonthlyKunjunganTable from './Dashboard/KunjunganBulanan';
import MonthlyKonsulTable from './Dashboard/KonsulBulanan';
import MonthlyMutasiTable from './Dashboard/MutasiBulanan';
import Statistic from './Dashboard/Statistic';
import KunjunganHarian from './Dashboard/KunjunganHarian';
import RajalBulanan from './Dashboard/RajalBulanan'
import DaruratBulanan from './Dashboard/DaruratBulanan'
import RanapBulanan from './Dashboard/RanapBulanan'

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
    pendaftaranBulanan,
    kunjunganBulanan,
    konsulBulanan,
    mutasiBulanan,
    statistics,
    statistikKunjungan,
    rawatJalanBulanan,
    rawatDaruratBulanan,
    rawatInapBulanan,
}) {

    const today = new Date();
    const formattedDate = today.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }) + ' ' + today.toLocaleTimeString('id-ID', {
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
    });

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Beranda" />

            <div className="max-w-full mx-auto sm:px-5 lg:px- w-full pt-3">
                <h1 className="uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400">
                    Data Hari Ini <br />
                    <span className='text-lg'>{formattedDate}</span>
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

            <h1 className="pt-2 uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400 mb-2">
                Data Tahun Ini
            </h1>
            <div className="pb-2 flex flex-wrap w-full">
                <Statistic
                    BOR={statistics.BOR || 0}
                    AVLOS={statistics.AVLOS || 0}
                    BTO={statistics.BTO || 0}
                    TOI={statistics.TOI || 0}
                    NDR={statistics.NDR || 0}
                    GDR={statistics.GDR || 0}
                />
            </div>

            <div className="pt-0 pb-5 flex flex-wrap w-full">
                <div className="w-1/4">
                    <RajalBulanan rawatJalanBulanan={rawatJalanBulanan} />
                </div>
                <div className="w-1/4">
                    <DaruratBulanan rawatDaruratBulanan={rawatDaruratBulanan} />
                </div>
                <div className="w-1/4">
                    <RanapBulanan rawatInapBulanan={rawatInapBulanan} />
                </div>
                <div className="w-1/4">
                    <MonthlyMutasiTable mutasiBulanan={mutasiBulanan} />
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
