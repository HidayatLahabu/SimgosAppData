import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import TodayData from './Dashboard/TodayData';
import MonthlyPendaftaranTable from './Dashboard/PendaftaranBulanan';
import MonthlyKunjunganTable from './Dashboard/KunjunganBulanan';
import MonthlyKonsulTable from './Dashboard/KonsulBulanan';
import MonthlyMutasiTable from './Dashboard/MutasiBulanan';
import Statistic from './Dashboard/Statistic';

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
}) {
    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Beranda" />

            <div className="pt-5 pb-2 flex flex-wrap w-full">
                <TodayData
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
            <h1 className="uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400 mb-2">
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
                    <MonthlyPendaftaranTable pendaftaranBulanan={pendaftaranBulanan} />
                </div>
                <div className="w-1/4">
                    <MonthlyKunjunganTable kunjunganBulanan={kunjunganBulanan} />
                </div>
                <div className="w-1/4">
                    <MonthlyKonsulTable konsulBulanan={konsulBulanan} />
                </div>
                <div className="w-1/4">
                    <MonthlyMutasiTable mutasiBulanan={mutasiBulanan} />
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
