import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';

export default function Print({ data, dariTanggal, sampaiTanggal }) {

    useEffect(() => {
        import('@/../../resources/css/print.css');
    }, []);

    const isBPJS = data.some(item => item.CARABAYAR?.toLowerCase().includes("bpjs"));

    return (
        <div className="h-screen w-screen bg-white">
            <Head title="Laporan" />

            <div className="content">
                <div className="w-full mx-auto sm:px-6 lg:px-5">
                    <div className="w-full bg-white overflow-hidden">
                        <div className="p-2 bg-white">
                            <div className="overflow-auto">
                                <h1 className="text-center font-bold text-2xl">
                                    LAPORAN RESPOND TIME IGD
                                </h1>
                                <p className="text-center font-bold text-2xl">
                                    {new Date(dariTanggal).toLocaleDateString() === new Date(sampaiTanggal).toLocaleDateString()
                                        ? `Tanggal : ${formatDate(sampaiTanggal)}`
                                        : `Selang Tanggal : ${formatDate(dariTanggal)} s.d ${formatDate(sampaiTanggal)}`}
                                </p>

                                <table className="w-full text-[10px] text-left rtl:text-right text-gray-500 dark:text-gray-900 mt-4 border border-gray-500">
                                    <thead className="text-[10px] font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[3%]">NO</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[5%]">NORM</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">NAMA PASIEN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">RUANGAN IGD</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center text-wrap w-[7%]">REGISTER IGD</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center text-wrap w-[7%]">KELUAR IGD</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center text-wrap w-[7%]">WAKTU PELAYANAN IGD</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap">RUANGAN IRNA</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center text-wrap w-[7%]">REGISTER IRNA</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center text-wrap w-[7%]">TERIMA IRNA</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center text-wrap w-[7%]">RESPOND TIME IRNA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item, key) => {
                                            return (
                                                <tr key={item.NORM}>
                                                    <td className="px-3 py-2 text-nowrap text-center border border-gray-500 border-solid">{key + 1}</td>
                                                    <td className="px-3 py-2 border border-gray-500 border-solid">
                                                        {item.NORM.replace(/-/g, '')}
                                                    </td>
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                        {item.NAMALENGKAP}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {item.RUANGAN_TRIASE}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap text-center border border-gray-500 border-solid">
                                                        {item.TGL_REG_TRIASE}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap text-center border border-gray-500 border-solid">
                                                        {item.TGL_KELUAR_IRD}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap text-center border border-gray-500 border-solid">
                                                        {item.SELISIH6}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {item.RUANGAN_IRNA}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap text-center border border-gray-500 border-solid">
                                                        {item.TGL_REG_IRNA}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap text-center border border-gray-500 border-solid">
                                                        {item.TGL_TERIMA_RUANGAN}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap text-center border border-gray-500 border-solid">
                                                        {item.SELISIH2}
                                                    </td>
                                                </tr>
                                            );
                                        })}
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
