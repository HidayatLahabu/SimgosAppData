import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import DashboardTable from './DashboardTable';
import SatuSehatTable from './SatuSehatTable';

export default function Dashboard({ auth, items }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Beranda" />

            <div className="py-5 flex flex-wrap w-full">
                <SatuSehatTable items={items} />
            </div>
        </AuthenticatedLayout>
    );
}
