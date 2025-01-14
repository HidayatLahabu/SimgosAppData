import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';

export default function Print({
    items,
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
                                    LAPORAN RL 3.1
                                </h1>
                                <p className="text-center">
                                    <strong>Periode Tanggal: {formatDate(tgl_awal)} s.d {formatDate(tgl_akhir)} </strong>
                                </p>

                                <div className="py-3 overflow-auto">
                                    <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                                        <thead className="uppercase font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                                            <tr>
                                                <th className="px-3 py-2 border border-gray-500 border-solid w-[15%]">JENIS PELAYANAN</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">PASIEN AWAL</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">PASIEN MASUK</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">PASIEN KELUAR</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">MATI &lt; 48</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">MATI &gt; 48</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">JUMLAH LAMA</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">PASIEN AKHIR</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">JUMLAH HARI</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">VVIP</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">VIP</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">KELAS I</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">KELAS II</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">KELAS III</th>
                                                <th className="px-3 py-2 border text-center border-gray-500 border-solid">KELAS KHUSUS</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            {items && items.data && items.data.length > 0 ? (
                                                items.data.map((data, index) => (
                                                    <tr key={data.KODE} className="border-b bg-white dark:border-gray-500">
                                                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                            {data.DESKRIPSI}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                            {formatRibuan(data.AWAL)}
                                                        </td>
                                                        <td className="text-center px-3 py-2 border border-gray-500 border-solid">
                                                            {formatRibuan(parseFloat(data.MASUK || 0) + parseFloat(data.PINDAHAN || 0))}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                            {formatRibuan(parseFloat(data.DIPINDAHKAN || 0) + parseFloat(data.HIDUP || 0))}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                            {formatRibuan(data.MATIKURANG48)}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                            {formatRibuan(data.MATILEBIH48)}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                            {formatRibuan(data.LD)}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                            {formatRibuan(data.SISA)}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                            {formatRibuan(data.HP)}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                            {formatRibuan(data.VVIP)}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                            {formatRibuan(data.VIP)}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                            {formatRibuan(data.KLSI)}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                            {formatRibuan(data.KLSII)}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                            {formatRibuan(data.KLSIII)}
                                                        </td>
                                                        <td className="text-center px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                            {formatRibuan(data.KLSKHUSUS)}
                                                        </td>
                                                    </tr>
                                                ))
                                            ) : (
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
