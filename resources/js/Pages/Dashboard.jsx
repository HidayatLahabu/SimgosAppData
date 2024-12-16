import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import SatuSehatTable from './Dashboard/SatuSehatTable';
import TodayData from './Dashboard/TodayData';
import MonthlyPendaftaranTable from './Dashboard/PendaftaranBulanan';
import MonthlyKunjunganTable from './Dashboard/KunjunganBulanan';
import MonthlyKonsulTable from './Dashboard/KonsulBulanan';
import MonthlyMutasiTable from './Dashboard/MutasiBulanan';

export default function Dashboard({
    auth,
    items,
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
            <div>

            </div>
            <div className="py-5 flex flex-wrap w-full">
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

            <div className="pt-0 pb-5 flex flex-wrap w-full">
                <SatuSehatTable items={items} />
            </div>
        </AuthenticatedLayout>
    );
}
