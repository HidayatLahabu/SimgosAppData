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
                                    LAPORAN RP1 - REKAP SENSUS HARIAN
                                </h1>
                                <h2 className="text-center font-bold text-2xl uppercase">
                                    {ruangan ? `RUANGAN ${ruangan}` : ''}
                                </h2>
                                <p className="text-center font-bold text-2xl">
                                    {new Date(dariTanggal).toLocaleDateString() === new Date(sampaiTanggal).toLocaleDateString()
                                        ? `Tanggal : ${formatDate(sampaiTanggal)}`
                                        : `Selang Tanggal : ${formatDate(dariTanggal)} s.d ${formatDate(sampaiTanggal)}`}
                                </p>

                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-900 mt-4 border border-gray-500">
                                    <thead className="text-[11px] font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">TANGGAL</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[5%]">AWAL</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[5%]">MASUK</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[5%]">PINDAHAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[5%]">DIPINDAHKAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center text-wrap w-[5%]">KELUAR HIDUP</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center text-wrap w-[4%]">MENINGGAL &lt; 48</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[7%]">MENINGGAL &gt; 48</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[5%]">PASIEN AKHIR</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[5%]">LAMA DIRAWAT</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[7%]">HARI PERAWATAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[5%]">RAWAT PAVILIUN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[5%]">RAWAT VVIP</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[5%]">RAWAT VIP</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[5%]">RAWAT KELAS I</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[5%]">RAWAT KELAS II</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[5%]">RAWAT KELAS III</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-wrap text-center w-[5%]">RAWAT KHUSUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item, key) => {
                                            return (
                                                <tr key={item.TANGGAL} className="border-b bg-white dark:border-gray-500 text-xs">
                                                    <td className="px-3 py-2 text-nowrap text-[11px] border border-gray-500 border-solid">
                                                        {formatDate(item.TANGGAL)}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.AWAL || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.MASUK || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.PINDAHAN || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.DIPINDAHKAN || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.HIDUP || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.MATIKURANG48 || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.MATILEBIH48 || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.SISA || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.LD || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.HP || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.PAV || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.VVIP || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.VIP || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.KLSI || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.KLSII || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.KLSIII || 0}
                                                    </td>
                                                    <td className="px-3 py-2 text-center text-[11px] border border-gray-500 border-solid">
                                                        {item.KLSKHUSUS || 0}
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
