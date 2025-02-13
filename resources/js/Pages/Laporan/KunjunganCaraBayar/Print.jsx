import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';

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
                                    LAPORAN KUNJUNGAN PER CARA BAYAR {ruangan ? `RUANGAN ${ruangan}` : ''}
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
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[5%]">ID</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">CARA BAYAR</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[9%]">LAKI-LAKI</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[9%]">PEREMPUAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">BARU</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">LAMA</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">JUMLAH</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item, key) => {
                                            return (
                                                <tr key={item.IDCARABAYAR} className="border-b bg-white dark:border-gray-500">
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">{key + 1}</td>
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                        {item.IDCARABAYAR || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                        {item.CARABAYAR || 'Tidak Teridentifikasi'}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.LAKILAKI) || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.PEREMPUAN) || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.BARU) || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.LAMA) || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.JUMLAH) || 0}
                                                    </td>
                                                </tr>
                                            );
                                        })}
                                    </tbody>
                                    <tfoot>
                                        <tr className='bg-gray-300 font-bold text-sm'>
                                            <td colSpan={3} className='px-2 py-2 text-right border border-gray-500 border-solid'>TOTAL</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.LAKILAKI) || 0}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.PEREMPUAN) || 0}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.BARU) || 0}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.LAMA) || 0}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.JUMLAH) || 0}</td>
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
