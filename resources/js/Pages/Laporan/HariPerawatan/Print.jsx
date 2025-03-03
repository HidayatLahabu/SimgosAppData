import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';

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
                                    LAPORAN HARI PERAWATAN
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
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[10%]">CARA BAYAR</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[6%]">NOPEN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center text-wrap w-[7%]">TANGGAL MASUK</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center text-wrap w-[7%]">TANGGAL KELUAR</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap">RUANGAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap">KAMAR</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[9%]">HARI RAWAT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item, key) => {
                                            return (
                                                <tr key={item.NORM} className={`border-b bg-white dark:border-gray-500 
                                                    ${item.JMLHP < 0 ? 'text-red-500 font-bold' : ''}`}>
                                                    <td className="px-3 py-2 text-nowrap text-center border border-gray-500 border-solid">{key + 1}</td>
                                                    <td className="px-3 py-2 border border-gray-500 border-solid">
                                                        {item.NORM.replace(/-/g, '')}
                                                    </td>
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                        {item.NAMALENGKAP}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {item.CARABAYAR}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {item.NOPEN}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap text-center border border-gray-500 border-solid">
                                                        {item.TGLMASUK}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap text-center border border-gray-500 border-solid">
                                                        {item.TGLKELUAR}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {item.UNIT}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {item.KAMAR}
                                                    </td>
                                                    <td className="px-3 py-2 text-nowrap text-center border border-gray-500 border-solid">
                                                        {item.JMLHP} Hari
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
