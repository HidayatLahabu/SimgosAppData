import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';
import { formatNumber } from '@/utils/formatNumber';

export default function Print({ data, dariTanggal, sampaiTanggal }) {

    useEffect(() => {
        import('@/../../resources/css/print.css');
    }, []);

    return (
        <div className="h-screen w-screen bg-white">
            <Head title="Laporan" />

            <div className="content">
                <div className="w-full mx-auto sm:px-6 lg:px-5">
                    <div className="w-full bg-white overflow-hidden">
                        <div className="p-2 bg-white">
                            <div className="overflow-auto">
                                <h1 className="text-center font-bold text-2xl">
                                    LAPORAN PASIEN BELUM FINAL GROUPING
                                </h1>
                                <p className="text-center font-bold text-2xl">
                                    {new Date(dariTanggal).toLocaleDateString() === new Date(sampaiTanggal).toLocaleDateString()
                                        ? `Tanggal : ${formatDate(sampaiTanggal)}`
                                        : `Selang Tanggal : ${formatDate(dariTanggal)} s.d ${formatDate(sampaiTanggal)}`}
                                </p>

                                <table className="w-full text-[10px] text-left rtl:text-right text-gray-500 dark:text-gray-900 mt-4 border border-gray-500">
                                    <thead className="text-[10px] font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[3%]">NO</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[6%]">TANGGAL MASUK</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[6%]">TANGGAL PULANG</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[5%]">NOMOR PENDAFTARAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[5%]">NORM</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">NAMA PASIEN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[6%]">NOMOR KARTU</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center">SEP</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">DOKTER</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center">LOS</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-right">TARIF RS</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">UNIT PELAYANAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item, key) => {
                                            return (
                                                <tr key={item.NOPEN} className="border-b bg-white dark:border-gray-500">
                                                    <td className="px-2 py-2 text-nowrap text-center text-[10px] border border-gray-500 border-solid">{key + 1}</td>
                                                    <td className="px-2 py-2 text-wrap text-center text-[10px] border border-gray-500 border-solid">
                                                        {item.TGLMASUK}
                                                    </td>
                                                    <td className="px-2 py-2 text-wrap text-center text-[10px] border border-gray-500 border-solid">
                                                        {item.TGLKELUAR}
                                                    </td>
                                                    <td className="px-2 py-2 text-nowrap text-[10px] text-center border border-gray-500 border-solid">
                                                        {item.NOMOR}
                                                    </td>
                                                    <td className="px-2 py-2 text-[10px] border border-gray-500 border-solid">
                                                        {item.NORM}
                                                    </td>
                                                    <td className="px-2 py-2 text-[10px] border border-gray-500 border-solid">
                                                        {item.NAMAPASIEN}
                                                    </td>
                                                    <td className="px-2 py-2 text-nowrap text-[10px] text-center border border-gray-500 border-solid">
                                                        {item.NOMORKARTU}
                                                    </td>
                                                    <td className="px-2 py-2 text-nowrap text-[10px] text-center border border-gray-500 border-solid">
                                                        {item.NOSEP}
                                                    </td>
                                                    <td className="px-2 py-2 text-nowrap text-[10px] border border-gray-500 border-solid">
                                                        {item.DPJP}
                                                    </td>
                                                    <td className="px-2 py-2 text-nowrap text-[10px] text-center border border-gray-500 border-solid">
                                                        {item.LOS}
                                                    </td>
                                                    <td className="px-2 py-2 text-nowrap text-right text-[10px] border border-gray-500 border-solid">
                                                        {formatNumber(item.TARIFRS)}
                                                    </td>
                                                    <td className="px-2 py-2 text-nowrap text-[10px] border border-gray-500 border-solid">
                                                        {item.UNITPELAYANAN}
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
