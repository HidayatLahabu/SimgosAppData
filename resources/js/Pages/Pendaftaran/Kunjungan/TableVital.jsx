import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/ButtonBack';
import ButtonDetail from "@/Components/ButtonDetail";

export default function TableRme({ auth, dataTable, nomorKunjungan, nomorPendaftaran, namaPasien, normPasien }) {

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Pendaftaran" />
            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="relative flex items-center justify-between pb-2">
                                    <ButtonBack href={route("kunjungan.tableRme", { id: nomorKunjungan })} />
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">DAFTAR TANDA VITAL</h1>
                                </div>
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
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">NOMOR KUNJUNGAN</th>
                                            <th className="px-3 py-2">ID</th>
                                            <th className="px-3 py-2">TANGGAL</th>
                                            <th className="px-3 py-2">OLEH</th>
                                            <th className="px-3 py-2">STATUS</th>
                                            <th className="px-3 py-2">MENU</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {dataTable.length > 0 ? (
                                            dataTable.map((item, index) => (
                                                <tr key={index} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                    <td className="px-3 py-3">{item.kunjungan}</td>
                                                    <td className="px-3 py-3">{item.id}</td>
                                                    <td className="px-3 py-3">{item.tanggal}</td>
                                                    <td className="px-3 py-3">{item.oleh}</td>
                                                    <td className="px-3 py-3">{item.status}</td>
                                                    <td className="px-3 py-3">
                                                        {item.id ? (
                                                            <ButtonDetail href={route("kunjungan.detailTandaVital", { id: item.id })} />
                                                        ) : (
                                                            <span className="text-gray-500">No detail available</span>
                                                        )}
                                                    </td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr>
                                                <td colSpan="8" className="text-center px-3 py-3">
                                                    No data available
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
