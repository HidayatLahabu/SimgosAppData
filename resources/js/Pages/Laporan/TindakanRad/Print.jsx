import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';
import { formatNumber } from '@/utils/formatNumber';

export default function Print({ data, total, dariTanggal, sampaiTanggal, ruangan }) {

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
                                <h1 className="text-center font-bold text-2xl uppercase">
                                    LAPORAN GROUP TINDAKAN RADIOLOGI
                                </h1>
                                <p className="text-center font-bold text-2xl">
                                    {new Date(dariTanggal).toLocaleDateString() === new Date(sampaiTanggal).toLocaleDateString()
                                        ? `Tanggal : ${formatDate(sampaiTanggal)}`
                                        : `Selang Tanggal : ${formatDate(dariTanggal)} s.d ${formatDate(sampaiTanggal)}`}
                                </p>

                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-900 mt-4 border border-gray-500">
                                    <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[3%]">NO</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">TINDAKAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">UMUM</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">BPJS</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">IKS</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">FREKUENSI</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">TAGIHAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item, key) => {
                                            return (
                                                <tr key={item.LAYANAN} className="border-b bg-white dark:border-gray-500">
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">{key + 1}</td>
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                        {item.LAYANAN}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.UMUM) || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.BPJS) || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.IKS) || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.FREKUENSI) || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-right border border-gray-500 border-solid">
                                                        {formatNumber(item.TOTALTAGIHAN) || 0}
                                                    </td>
                                                </tr>
                                            );
                                        })}
                                    </tbody>
                                    <tfoot>
                                        <tr className='bg-gray-300 font-bold text-sm'>
                                            <td colSpan={2} className='px-2 py-2 text-right border border-gray-500 border-solid'>TOTAL</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.UMUM) || 0}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.BPJS) || 0}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.IKS) || 0}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.FREKUENSI) || 0}</td>
                                            <td className="px-2 py-2 text-right border border-gray-500 border-solid">{formatRibuan(total.TOTALTAGIHAN) || 0}</td>
                                        </tr>
                                    </tfoot>
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
