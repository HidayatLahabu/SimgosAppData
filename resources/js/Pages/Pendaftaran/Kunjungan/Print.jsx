import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';

export default function Print({
    data,
    dariTanggal,
    sampaiTanggal,
    namaRuangan,
    namaStatusKunjungan,
    jenisPasien
}) {

    useEffect(() => {
        import('@/../../resources/css/print.css');
    }, []);

    return (
        <div className="h-screen w-screen bg-white">
            <Head title="Pendaftaran" />

            <div className="content">
                <div className="w-full mx-auto sm:px-6 lg:px-5">
                    <div className="w-full bg-white overflow-hidden">
                        <div className="p-2 bg-white">
                            <div className="overflow-auto">
                                <h1 className="text-center font-bold text-2xl">
                                    DATA KUNJUNGAN PASIEN
                                </h1>
                                <h2 className="text-center font-bold text-2xl uppercase">
                                    NAMA RUANGAN : {namaRuangan}
                                </h2>
                                <h2 className="text-center font-bold text-2xl uppercase">
                                    STATUS AKTIFITAS KUNJUNGAN : {namaStatusKunjungan}
                                </h2>
                                <h2 className="text-center font-bold text-2xl uppercase">
                                    STATUS KUNJUNGAN : {jenisPasien}
                                </h2>
                                <p className="text-center font-bold text-2xl">
                                    Selang Tanggal : {formatDate(dariTanggal)} s.d {formatDate(sampaiTanggal)}
                                </p>

                                <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 mt-4 border border-gray-500">
                                    <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500 ">
                                        <tr>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[3%]">NO</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[7%]">NORM</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">NAMA PASIEN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[10%] text-center">NOHP</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[12%] text-center">NOMOR KUNJUNGAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[12%]">TANGGAL MASUK</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[12%]">TANGGAL KELUAR</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">RUANGAN TUJUAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[5%] text-center">STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item, key) => (
                                            <tr key={item.id} className="border-b bg-white dark:border-gray-500">
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">{key + 1}</td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.norm}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.nama}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.nohp}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.nomor}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.masuk}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.keluar}
                                                </td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">{item.ruangan}</td>
                                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                    {item.status === 0 ? 'Batal Kunjungan' : item.status === 1 ? 'Sedang Dilayani' : 'Selesai'}
                                                </td>

                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <footer className="bg-white text-black text-sm">
                    <div className="text-center">
                        <p>&copy; {new Date().getFullYear()} Hidayat - Tim IT RSUD Dr. M. M. Dunda Limboto. All rights reserved.</p>
                    </div>
                </footer>
            </div>
        </div>
    );
}
