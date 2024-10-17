import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';

export default function Print({ data, dariTanggal, sampaiTanggal, jenisPasien }) {

    useEffect(() => {
        import('@/../../resources/css/print.css');
    }, []);

    return (
        <div className="h-screen w-screen bg-white">
            <Head title="Layanan" />

            <div className="content">
                <div className="w-full mx-auto sm:px-6 lg:px-5">
                    <div className="w-full bg-white overflow-hidden">
                        <div className="p-2 bg-white">
                            <div className="overflow-auto">
                                <h1 className="text-center font-bold text-2xl">
                                    LAYANAN LABORATORIUM
                                </h1>
                                <p className="text-center font-bold text-2xl">
                                    Selang Tanggal : {formatDate(dariTanggal)} s.d {formatDate(sampaiTanggal)}
                                </p>

                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-900 mt-4">
                                    <thead className="text-sm font-bold text-gray-900 bg-white dark:text-gray-900 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2 text-center">NO</th>
                                            <th className="px-3 py-2 text-center">ORDER NOMOR</th>
                                            <th className="px-3 py-2 text-center">TANGGAL</th>
                                            <th className="px-3 py-2">NORM</th>
                                            <th className="px-3 py-2">NAMA PASIEN</th>
                                            <th className="px-3 py-2 text-center">JENIS PASIEN</th>
                                            <th className="px-3 py-2 text-right">STATUS HASIL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item, key) => (
                                            <tr key={item.id} className="border-b bg-white dark:border-gray-500">
                                                <td className="px-2 py-2 text-center">{key + 1}</td>
                                                <td className="px-3 py-2 text-center">
                                                    {item.nomor}
                                                </td>
                                                <td className="px-3 py-2 text-center">
                                                    {formatDate(item.tanggal)}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap">
                                                    {item.norm}
                                                </td>
                                                <td className="px-3 py-2">{item.nama}</td>
                                                <td className="px-3 py-2 text-center">
                                                    {jenisPasien}
                                                </td>
                                                <td className="px-3 py-2 text-right">
                                                    {item.statusHasil}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <footer className="bg-white text-black text-sm">
                    <div className="text-center">
                        <p>&copy; {new Date().getFullYear()} Hidayat - Tim IT RSUD Dr. M. M. Dunda Limboto. All rights reserved.</p>
                    </div>
                </footer>
            </div>
        </div>
    );
}
