import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import Pagination from "@/Components/Pagination";

export default function Index({ auth, stockDetail }) {
    return (
        <AuthenticatedLayout user={auth.user}>

            <Head title="Inventory" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-xl pb-2">
                                    DATA STOCK OPNAME DETIL
                                </h1>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm font-bold text-gray-700 bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">NAMA BARANG</th>
                                            <th className="px-3 py-2">SATUAN</th>
                                            <th className="px-3 py-2 text-right">AWAL</th>
                                            <th className="px-3 py-2 text-right">MANUAL</th>
                                            <th className="px-3 py-2 text-right">MASUK</th>
                                            <th className="px-3 py-2 text-right">KELUAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {stockDetail.data.length > 0 ? (
                                            stockDetail.data.map((stockDetail, index) => (
                                                <tr key={stockDetail.id} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                    <td className="px-3 py-3">{stockDetail.nama}</td>
                                                    <td className="px-3 py-3">{stockDetail.satuan}</td>
                                                    <td className="px-3 py-3 text-right">{stockDetail.awal}</td>
                                                    <td className="px-3 py-3 text-right">{stockDetail.manual}</td>
                                                    <td className="px-3 py-3 text-right">{stockDetail.masuk}</td>
                                                    <td className="px-3 py-3 text-right">{stockDetail.keluar}</td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="6" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                                <Pagination links={stockDetail.links} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
