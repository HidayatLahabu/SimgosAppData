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
                                    LAPORAN LAPORAN KEGIATAN RAWAT INAP
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
                                            <th className="px-3 py-2 border border-gray-500 border-solid">DESKRIPSI</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">AWAL</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">MASUK</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">PINDAHAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">DIPINDAHKAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">HIDUP</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">MENINGGAL</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[7%]">MENINGGAL &lt; 48</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[7%]">MENINGGAL &gt; 48</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[7%]">AKHIR</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[7%]">LAMA DIRAWAT</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[7%]">HARI PERAWATAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item, key) => {
                                            return (
                                                <tr key={item.ID} className="border-b bg-white dark:border-gray-500">
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">{key + 1}</td>
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                        {item.DESKRIPSI}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.AWAL)}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.MASUK)}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.PINDAHAN)}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.DIPINDAHKAN)}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.HIDUP)}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.MATI)}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.MATIKURANG48)}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.MATILEBIH48)}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.SISA)}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.LD)}
                                                    </td>
                                                    <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                                        {formatRibuan(item.HP)}
                                                    </td>
                                                </tr>
                                            );
                                        })}
                                    </tbody>
                                    <tfoot>
                                        <tr className='bg-gray-300 font-bold text-sm'>
                                            <td colSpan={2} className='px-2 py-2 text-right border border-gray-500 border-solid'>TOTAL</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.AWAL)}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.MASUK)}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.PINDAHAN)}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.DIPINDAHKAN)}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.HIDUP)}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.MATI)}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.MATIKURANG48)}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.MATILEBIH48)}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.SISA)}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.LD)}</td>
                                            <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.HP)}</td>
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
