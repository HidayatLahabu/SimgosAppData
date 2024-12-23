import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import TodayData from './Dashboard/TodayData';
import MonthlyPendaftaranTable from './Dashboard/PendaftaranBulanan';
import MonthlyKunjunganTable from './Dashboard/KunjunganBulanan';
import MonthlyKonsulTable from './Dashboard/KonsulBulanan';
import MonthlyMutasiTable from './Dashboard/MutasiBulanan';

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
            <h1 className="uppercase text-center font-bold text-2xl pb-2 text-yellow-400">
                Data Bulanan Tahun {new Date().getFullYear()}
            </h1>
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
