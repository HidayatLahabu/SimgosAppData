import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';

export default function Print({
    data,
    tgl_awal,
    tgl_akhir
}) {

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
                                    LAPORAN RL 3.14 - RUJUKAN
                                </h1>
                                <p className="text-center">
                                    <strong>Periode Tanggal: {formatDate(tgl_awal)} s.d {formatDate(tgl_akhir)} </strong>
                                </p>

                                <div className="py-3 overflow-auto">
                                    <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                                        <thead className="uppercase font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                                            <tr>
                                                <th className="px-3 py-2 border border-gray-500 border-solid">JENIS SPESIALISASI</th>
                                                <th className="px-3 py-2 text-wrap text-center border border-gray-500 border-solid w-[9%]">RUJUKAN DITERIMA DARI PUSKESMAS</th>
                                                <th className="px-3 py-2 border text-wrap text-center border-gray-500 border-solid w-[9%]">RUJUKAN DITERIMA DARI FASILITAS KESEHATAN</th>
                                                <th className="px-3 py-2 border text-wrap text-center border-gray-500 border-solid w-[9%]">RUJUKAN DITERIMA DARI RS LAIN</th>
                                                <th className="px-3 py-2 border text-wrap text-center border-gray-500 border-solid w-[9%]">RUJUKAN DIKEMBALIKAN KE PUSKESMAS</th>
                                                <th className="px-3 py-2 border text-wrap text-center border-gray-500 border-solid w-[9%]">RUJUKAN DIKEMBALIKAN KE FASILITAS KESEHATAN</th>
                                                <th className="px-3 py-2 border text-wrap text-center border-gray-500 border-solid w-[9%]">RUJUKAN DIKEMBALIKAN KE RS ASAL</th>
                                                <th className="px-3 py-2 border text-wrap text-center border-gray-500 border-solid w-[9%]">DIRUJUK PASIEN RUJUKAN</th>
                                                <th className="px-3 py-2 border text-wrap text-center border-gray-500 border-solid w-[9%]">DIRUJUK DATANG SENDIRI</th>
                                                <th className="px-3 py-2 border text-wrap text-center border-gray-500 border-solid w-[9%]">DIRUJUK DITERIMA KEMBALI</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            {data.map((item, index) => (
                                                <tr key={item.KODERS} className="border-b bg-white dark:border-gray-500">
                                                    <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {item.DESKRIPSI}
                                                    </td>
                                                    <td className="text-center px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {formatRibuan(item.PUSKESMAS)}
                                                    </td>
                                                    <td className="text-center px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {formatRibuan(item.FASKES)}
                                                    </td>
                                                    <td className="text-center px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {formatRibuan(item.RS)}
                                                    </td>
                                                    <td className="text-center px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {formatRibuan(item.KEMBALIPUSKESMAS)}
                                                    </td>
                                                    <td className="text-center px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {formatRibuan(item.KEMBALIFASKES)}
                                                    </td>
                                                    <td className="text-center px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {formatRibuan(item.KEMBALIRS)}
                                                    </td>
                                                    <td className="text-center px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {formatRibuan(item.PASIENRUJUKAN)}
                                                    </td>
                                                    <td className="text-center px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {formatRibuan(item.DATANGSENDIRI)}
                                                    </td>
                                                    <td className="text-center px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {formatRibuan(item.DITERIMAKEMBALI)}
                                                    </td>
                                                </tr>
                                            ))}
                                            {data.length === 0 && (
                                                <tr className="bg-white border border-gray-500 border-solid">
                                                    <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                                </tr>
                                            )}
                                        </tbody>
                                    </table>
                                </div>
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
