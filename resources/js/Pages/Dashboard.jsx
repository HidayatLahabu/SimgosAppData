import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import SatuSehatTable from './SatuSehatTable';
import TodayData from './TodayData';

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
}) {
    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Beranda" />
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
                />
            </div>
            <div className="pt-0 pb-5 flex flex-wrap w-full">
                <SatuSehatTable items={items} />
            </div>
        </AuthenticatedLayout>
    );
}
