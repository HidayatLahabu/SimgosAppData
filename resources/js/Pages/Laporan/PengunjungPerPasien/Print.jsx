import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';

export default function Print({ data, dariTanggal, sampaiTanggal }) {

    useEffect(() => {
        import('@/../../resources/css/print.css');
    }, []);

    // Fungsi untuk mengonversi waktu (format HH:MM:SS) ke menit
    function convertTimeToMinutes(seconds) {
        return Math.floor(seconds / 60); // Mengubah detik menjadi menit
    }

    return (
        <div className="h-screen w-screen bg-white">
            <Head title="Laporan" />

            <div className="content">
                <div className="w-full mx-auto sm:px-6 lg:px-5">
                    <div className="w-full bg-white overflow-hidden">
                        <div className="p-2 bg-white">
                            <div className="overflow-auto">
                                <h1 className="text-center font-bold text-2xl">
                                    LAPORAN PENGUNJUNG PER PASIEN
                                </h1>
                                <p className="text-center font-bold text-2xl">
                                    {new Date(dariTanggal).toLocaleDateString() === new Date(sampaiTanggal).toLocaleDateString()
                                        ? `Tanggal : ${formatDate(sampaiTanggal)}`
                                        : `Selang Tanggal : ${formatDate(dariTanggal)} s.d ${formatDate(sampaiTanggal)}`}
                                </p>

                                <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 mt-4 border border-gray-500">
                                    <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[3%]">NO</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[6%]">PENDAFTARAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[5%]">NORM</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">NAMA PASIEN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid w-[4%]">STATUS</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[10%]">TANGGAL REGISTRASI</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[10%]">TANGGAL KELUAR</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">CARA BAYAR</th>

                                            <th className="px-3 py-2 border border-gray-500 border-solid">UNIT PELAYANAN</th>
                                            <th className="px-3 py-2 border border-gray-500 border-solid">DOKTER</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item, key) => {
                                            return (
                                                <tr key={item.NOPEN} className="border-b bg-white dark:border-gray-500">
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">{key + 1}</td>
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                        {item.NOPEN}
                                                    </td>
                                                    <td className="px-3 py-2 border border-gray-500 border-solid">
                                                        {item.NORM}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {item.NAMA_LENGKAP}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                                                        {item.STATUSPENGUNJUNG}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap text-center border border-gray-500 border-solid">
                                                        {item.TGLTERIMA}
                                                    </td>
                                                    <td className="px-3 py-2 text-wrap text-center border border-gray-500 border-solid">
                                                        {item.TGLKELUAR}
                                                    </td>
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                        {item.CARABAYAR}
                                                    </td>
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                        {item.UNITPELAYANAN}
                                                    </td>
                                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                                        {item.DOKTER_REG}
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
