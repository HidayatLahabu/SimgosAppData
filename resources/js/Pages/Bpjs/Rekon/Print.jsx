import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';

export default function Print({ data, dariTanggal, sampaiTanggal }) {

    useEffect(() => {
        import('@/../../resources/css/print.css');
    }, []);

    return (
        <div className="h-screen w-screen bg-white">
            <Head title="BPJS" />

            <div className="content">
                <div className="w-full mx-auto sm:px-6 lg:px-5">
                    <div className="w-full bg-white overflow-hidden">
                        <div className="p-2 bg-white">
                            <div className="overflow-auto">
                                <h1 className="text-center font-bold text-2xl">
                                    RENCANA KONTROL
                                </h1>
                                <p className="text-center font-bold text-2xl">
                                    {new Date(dariTanggal).toLocaleDateString() === new Date(sampaiTanggal).toLocaleDateString()
                                        ? `Tanggal : ${formatDate(sampaiTanggal)}`
                                        : `Selang Tanggal : ${formatDate(dariTanggal)} s.d ${formatDate(sampaiTanggal)}`}
                                </p>

                                <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 mt-4 border border-gray-500">
                                    <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[3%]">NO</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[9%]">NORM/ <br />NO BPJS</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">NAMA PASIEN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">NOMOR KONTROL</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">TANGGAL <br /> KONTROL</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">RUANGAN TUJUAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">DPJP</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">KETERANGAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item, key) => (
                                            <tr key={item.noSurat} className={`border-b bg-white dark:border-gray-500 ${item.kunjungan == null ? 'text-red-500' : ''}`}>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">{key + 1}</td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.norm || item.noKartu}
                                                </td>
                                                <td className="px-3 py-2 border border-gray-500 border-solid">
                                                    {item.namaPasien || item.pasienNama}
                                                </td>
                                                <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                    {item.noSurat}
                                                </td>
                                                <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                    {formatDate(item.tanggal)}
                                                </td>
                                                <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                    POLIKLINIK {item.poliTujuan}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.namaDokter}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.kunjungan != null ? "Datang Kontrol" : "Batal Kontrol"}
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
