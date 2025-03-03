import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';

export default function Print({ data, dariTanggal, sampaiTanggal, keadaanPulang, jenisPenjamin }) {

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
                                    LAYANAN PASIEN PULANG
                                </h1>
                                <h2 className="text-center font-bold text-2xl uppercase">
                                    JENIS PENJAMIN : {jenisPenjamin}
                                </h2>
                                <h2 className="text-center font-bold text-2xl uppercase">
                                    KEADAAN PULANG : {keadaanPulang}
                                </h2>
                                <p className="text-center font-bold text-2xl">
                                    Selang Tanggal : {formatDate(dariTanggal)} s.d {formatDate(sampaiTanggal)}
                                </p>

                                <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 mt-4 border border-gray-500">
                                    <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[4%]">NO</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[8%]">ID PULANG</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[12%]">TANGGAL</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[7%]">NORM</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">NAMA PASIEN</th>
                                            {jenisPenjamin === "BPJS KESEHATAN" && (
                                                <th className="px-3 py-2 border border-gray-500 border-solid w-[10%]">NOMOR SEP</th>
                                            )}
                                            <th className="px-3 py-2 border border-gray-500 border-solid">KEADAAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">DPJP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item, key) => (
                                            <tr key={item.idPulang} className="border-b bg-white dark:border-gray-500">
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">{key + 1}</td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.idPulang}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.tanggal}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.norm}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.namaPasien}
                                                </td>
                                                {jenisPenjamin === 'BPJS KESEHATAN' && (
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">{item.nomorSEP}</td>
                                                )}
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.keadaan}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.dpjp}
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
                        <p>&copy; 2024 - {new Date().getFullYear()} Hidayat - Tim IT RSUD Dr. M. M. Dunda Limboto.</p>
                    </div>
                </footer>
            </div>
        </div>
    );
}
