import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';

export default function Print({
    items,
    tgl_awal,
    tgl_akhir,
    total,
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
                                    LAPORAN REKAP MONITORING STATUS KEGIATAN
                                </h1>
                                <p className="text-center">
                                    <strong>Periode Tanggal: {formatDate(tgl_awal)} s.d {formatDate(tgl_akhir)} </strong>
                                </p>

                                <div className="py-3 overflow-auto">
                                    <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                                        <thead className="uppercase font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                                            <tr>
                                                <th className="px-3 py-2 border border-gray-500 border-solid">RUANGAN PELAYANAN</th>
                                                <th className="px-3 py-2 border text-center text-wrap border-gray-500 border-solid w-[9%]">BELUM TERIMA PENGUNJUNG</th>
                                                <th className="px-3 py-2 border text-center text-wrap border-gray-500 border-solid w-[9%]">BELUM FINAL KUNJUNGAN</th>
                                                <th className="px-3 py-2 border text-center text-wrap border-gray-500 border-solid w-[9%]">BELUM TERIMA RESEP</th>
                                                <th className="px-3 py-2 border text-center text-wrap border-gray-500 border-solid w-[9%]">BELUM TERIMA LABORATORIUM</th>
                                                <th className="px-3 py-2 border text-center text-wrap border-gray-500 border-solid w-[9%]">BELUM TERIMA RADIOLOGI</th>
                                                <th className="px-3 py-2 border text-center text-wrap border-gray-500 border-solid w-[9%]">BELUM TERIMA KONSUL</th>
                                                <th className="px-3 py-2 border text-center text-wrap border-gray-500 border-solid w-[9%]">BELUM TERIMA MUTASI</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            {items && items.data && items.data.length > 0 ? (
                                                items.data.map((data, index) => (
                                                    <tr key={data.RUANGAN} className="border-b bg-white dark:border-gray-500">
                                                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">{data.DESKRIPSI}</td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">{formatRibuan(data.BLM_TERIMA_KUNJUNGAN)}</td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">{formatRibuan(data.BLM_FINAL_KUNJUNGAN)}</td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">{formatRibuan(data.BLM_TERIMA_RESEP)}</td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">{formatRibuan(data.BLM_TERIMA_LAB)}</td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">{formatRibuan(data.BLM_TERIMA_RAD)}</td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">{formatRibuan(data.BLM_TERIMA_KONSUL)}</td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">{formatRibuan(data.BLM_TERIMA_MUTASI)}</td>
                                                    </tr>
                                                ))
                                            ) : (
                                                <tr className="bg-white border border-gray-500 border-solid">
                                                    <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                                </tr>
                                            )}
                                        </tbody>
                                        <tfoot>
                                            <tr className='bg-gray-300 font-bold text-sm'>
                                                <td className='px-2 py-2 text-right border border-gray-500 border-solid'>TOTAL</td>
                                                <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.BLM_TERIMA_KUNJUNGAN)}</td>
                                                <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.BLM_FINAL_KUNJUNGAN)}</td>
                                                <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.BLM_TERIMA_RESEP)}</td>
                                                <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.BLM_TERIMA_LAB)}</td>
                                                <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.BLM_TERIMA_RAD)}</td>
                                                <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.BLM_TERIMA_KONSUL)}</td>
                                                <td className="px-2 py-2 text-center border border-gray-500 border-solid">{formatRibuan(total.BLM_TERIMA_MUTASI)}</td>
                                            </tr>
                                        </tfoot>
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
