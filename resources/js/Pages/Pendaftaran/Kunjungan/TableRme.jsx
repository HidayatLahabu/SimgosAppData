import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { Link } from '@inertiajs/react';
import ButtonBack from '@/Components/ButtonBack';

// Komponen reusable untuk data link
const DataLink = ({ href, label, fallback }) => (
    <div className="bg-white dark:bg-indigo-900 rounded-lg shadow-md p-5">
        {href ? (
            <Link href={href} className="block text-lg font-semibold text-gray-800 dark:text-gray-200 hover:underline">
                {label}
            </Link>
        ) : (
            <span className="block text-lg font-semibold text-red-800 dark:text-red-500">
                {fallback}
            </span>
        )}
    </div>
);

export default function TableRme({
    auth,
    nomorKunjungan,
    nomorPendaftaran,
    namaPasien,
    normPasien,
    diagnosa,
    anamnesis,
    askep,
    cppt,
    jadwalKontrol,
    tandaVital
}) {
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
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">
                                        DATA DETAIL KUNJUNGAN
                                    </h1>
                                </div>

                                {/* Informasi Detail */}
                                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 pb-4">
                                    <div className="flex justify-between border p-2 rounded">
                                        NOMOR PENDAFTARAN : {nomorPendaftaran}
                                    </div>
                                    <div className="flex justify-between border p-2 rounded">
                                        NOMOR KUNJUNGAN : {nomorKunjungan}
                                    </div>
                                    <div className="flex justify-between border p-2 rounded">
                                        NAMA PASIEN : {namaPasien}
                                    </div>
                                    <div className="flex justify-between border p-2 rounded">
                                        NORM : {normPasien}
                                    </div>
                                </div>

                                {/* Data Links */}
                                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <DataLink
                                        href={diagnosa ? route("kunjungan.diagnosa", { id: diagnosa }) : null}
                                        label="Data Diagnosa"
                                        fallback="Data Diagnosa Belum Ada"
                                    />
                                    <DataLink
                                        href={anamnesis ? route("kunjungan.anamnesis", { id: anamnesis }) : null}
                                        label="Data Anamnesis"
                                        fallback="Data Anamnesis Belum Ada"
                                    />
                                    <DataLink
                                        href={askep ? route("kunjungan.askep", { id: askep }) : null}
                                        label="Data Asuhan Keperawatan"
                                        fallback="Data Asuhan Keperawatan Belum Ada"
                                    />
                                    <DataLink
                                        href={cppt ? route("kunjungan.cppt", { id: cppt }) : null}
                                        label="Data CPPT"
                                        fallback="Data CPPT Belum Ada"
                                    />
                                    <DataLink
                                        href={tandaVital ? route("kunjungan.tandaVital", { id: tandaVital }) : null}
                                        label="Data Tanda Vital"
                                        fallback="Data Tanda Vital Belum Ada"
                                    />
                                    <DataLink
                                        href={jadwalKontrol ? route("kunjungan.jadwalKontrol", { id: jadwalKontrol }) : null}
                                        label="Data Jadwal Kontrol"
                                        fallback="Data Jadwal Kontrol Tidak Ada"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
