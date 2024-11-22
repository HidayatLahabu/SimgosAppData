import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { Link } from '@inertiajs/react';
import ButtonBack from '@/Components/ButtonBack';

export default function TableRme({ auth, nomorKunjungan, nomorPendaftaran }) {
    const data = [
        { title: 'Data Diagnosa', route: "kunjungan.diagnosa", id: nomorPendaftaran },
        { title: 'Data Anamnesis', route: "kunjungan.anamnesis", id: nomorKunjungan },
        { title: 'Data Asuhan Keperawatan', route: "kunjungan.askep", id: nomorKunjungan },
        { title: 'Data CPPT', route: "kunjungan.cppt", id: nomorKunjungan },
        { title: 'Data Tanda Vital', route: "kunjungan.vital", id: nomorKunjungan },
        { title: 'Data Jadwal Kontrol', route: "kunjungan.kontrol", id: nomorKunjungan },
        { title: 'Data Laboratorium', route: "kunjungan.laboratorium", id: nomorKunjungan },
        { title: 'Data Radiologi', route: "kunjungan.radiologi", id: nomorKunjungan },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Pendaftaran" />
            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="relative flex items-center justify-between pb-2">
                                    <Link href={route("kunjungan.detail", { id: nomorKunjungan })}>
                                        <ButtonBack href={route("kunjungan.detail", { id: nomorKunjungan })} />
                                    </Link>
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">DATA DETAIL KUNJUNGAN</h1>
                                </div>
                                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    {data.map((item, index) => (
                                        <div key={index} className="bg-white dark:bg-indigo-900 rounded-lg shadow-md p-5">
                                            <Link href={route(item.route, { id: item.id })} className="block text-lg font-semibold text-gray-800 dark:text-gray-200 hover:underline">
                                                {item.title}
                                            </Link>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
