import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import SatuSehatTable from './SatuSehatTable';


export default function Sinkronisasi({
    auth,
    items,
}) {
    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Satusehat" />

            <div className="py-5">
                <div className="flex flex-wrap w-full">
                    <SatuSehatTable items={items} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
