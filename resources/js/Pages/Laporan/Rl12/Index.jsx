import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { formatDate } from '@/utils/formatDate';
import { formatNumber } from '@/utils/formatNumber';

export default function LaporanRl12({ auth, items = [], tgl_awal, tgl_akhir }) {
    console.log('Received items:', items);

    if (!Array.isArray(items)) {
        console.error('Expected items to be an array but received:', items);
        return <div>Error: Data not available</div>;
    }

    const data = items[0] || {};

    const fieldMap = {
        KODERS: "Kode Rumah Sakit",
        NAMAINST: "Nama Instansi",
        KODEPROP: "Kode Provinsi",
        KOTA: "Kota/Kabupaten",
        TAHUN: "Tahun",
        AWAL: "Jumlah Awal",
        MASUK: "Jumlah Masuk",
        PINDAHAN: "Pindahan",
        DIPINDAHKAN: "Dipindahkan",
        HIDUP: "Pasien Hidup",
        MATI: "Pasien Mati",
        MATIKURANG48: "Mati < 48 Jam",
        MATILEBIH48: "Mati > 48 Jam",
        LD: "Lama Dirawat",
        SISA: "Sisa",
        HP: "Hari Perawatan",
        JMLTT: "Jumlah Tempat Tidur",
        BOR: "Bed Occupancy Rate",
        BORLK: "BOR Laki-laki",
        BORPR: "BOR Perempuan",
        AVLOS: "Average Length of Stay",
        AVLOSLK: "AVLOS Laki-laki",
        AVLOSPR: "AVLOS Perempuan",
        BTO: "Bed Turn Over",
        BTOLK: "BTO Laki-laki",
        BTOPR: "BTO Perempuan",
        TOI: "Turn Over Interval",
        TOILK: "TOI Laki-laki",
        TOIPR: "TOI Perempuan",
        NDR: "Net Death Rate",
        NDRLK: "NDR Laki-laki",
        NDRPR: "NDR Perempuan",
        GDR: "Gross Death Rate",
        GDRLK: "GDR Laki-laki",
        GDRPR: "GDR Perempuan",
        JMLKUNJUNGAN: "Jumlah Kunjungan",
    };

    const sections = {
        "Informasi Umum": ["KODERS", "NAMAINST", "KODEPROP", "KOTA", "TAHUN", "JMLTT"],
        "Data Pasien dan Perawatan": ["AWAL", "MASUK", "PINDAHAN", "DIPINDAHKAN", "HIDUP", "MATI", "MATIKURANG48", "MATILEBIH48", "NDR", "NDRLK", "NDRPR", "GDR", "GDRLK", "GDRPR", "LD"],
        "Data Tempat Tidur dan BOR": ["BOR", "BORLK", "BORPR", "AVLOS", "AVLOSLK", "AVLOSPR", "BTO", "BTOLK", "BTOPR", "TOI", "TOILK", "TOIPR", "JMLKUNJUNGAN", "HP", "SISA"],
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan RL 1.2" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">LAPORAN RL 1.2</h1>
                            <p className="text-center pb-2">
                                <strong>Periode Tanggal: </strong>{formatDate(tgl_awal)} - {formatDate(tgl_akhir)}
                            </p>
                            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div className="bg-white dark:bg-indigo-950 rounded-lg shadow-lg p-2">

                                    <table className="min-w-full table-auto mt-2 border border-gray-500 dark:border-gray-600">
                                        <thead>
                                            <tr>
                                                <th colSpan="2" className="text-normal font-semibold uppercase px-2 py-1 text-yellow-500 dark:bg-indigo-900">
                                                    Informasi Organisasi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {sections["Informasi Umum"].map((key) => (
                                                <tr key={key} className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600">
                                                    <td className="px-4 py-1 font-medium border border-collapse border-gray-400">{fieldMap[key]}</td>
                                                    <td className="px-4 py-1">{data[key] || '-'}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                                <div className="bg-white dark:bg-indigo-950 rounded-lg shadow-lg p-2">
                                    <table className="min-w-full table-auto mt-2 border border-gray-500 dark:border-gray-600">
                                        <thead>
                                            <tr>
                                                <th colSpan="2" className="text-normal font-semibold uppercase px-2 py-1 text-yellow-500 dark:bg-indigo-900">
                                                    Data Pasien dan Perawatan
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {sections["Data Pasien dan Perawatan"].map((key) => (
                                                <tr key={key} className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600">
                                                    <td className="px-4 py-1 font-medium border border-gray-500 dark:border-gray-600">{fieldMap[key]}</td>
                                                    <td className="px-4 py-1">{typeof data[key] === 'number' ? formatNumber(data[key]) : data[key] || '-'}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                                <div className="bg-white dark:bg-indigo-950 rounded-lg shadow-lg p-2">
                                    <table className="min-w-full table-auto mt-2 border border-gray-500 dark:border-gray-600">
                                        <thead>
                                            <tr>
                                                <th colSpan="2" className="text-normal font-semibold uppercase px-2 py-1 text-yellow-500 dark:bg-indigo-900">
                                                    Data Tempat Tidur dan Hunian
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {sections["Data Tempat Tidur dan BOR"].map((key) => (
                                                <tr key={key} className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600">
                                                    <td className="px-4 py-1 font-medium border border-gray-500 dark:border-gray-600">{fieldMap[key]}</td>
                                                    <td className="px-4 py-1">{typeof data[key] === 'number' ? formatNumber(data[key]) : data[key] || '-'}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
