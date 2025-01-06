import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { formatDate } from '@/utils/formatDate';
import { formatNumber } from '@/utils/formatNumber';

export default function LasYear({ items_last_year = [], tgl_awal_last_year, tgl_akhir_last_year }) {

    const data = items_last_year[0] || {};

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

    const keys = [
        "KODERS", "NAMAINST", "KODEPROP", "KOTA", "TAHUN", "JMLTT",
        "AWAL", "MASUK", "PINDAHAN", "DIPINDAHKAN", "HIDUP", "MATI",
        "MATIKURANG48", "MATILEBIH48", "NDR", "NDRLK", "NDRPR", "GDR",
        "GDRLK", "GDRPR", "LD", "BOR", "BORLK", "BORPR", "AVLOS",
        "AVLOSLK", "AVLOSPR", "BTO", "BTOLK", "BTOPR", "TOI", "TOILK",
        "TOIPR", "JMLKUNJUNGAN", "HP", "SISA"
    ];

    return (
        <div className="py-5 flex flex-wrap w-full">
            <div className="max-w-full mx-auto sm:pl-2 sm:pr-5 lg:pl-2 lg:pr-5 w-full">
                <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                    <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                        <h1 className="uppercase text-center font-bold text-2xl pb-2">LAPORAN RL 1.2</h1>
                        <p className="text-center pb-2">
                            <strong>Periode Tanggal: </strong>{formatDate(tgl_awal_last_year)} - {formatDate(tgl_akhir_last_year)}
                        </p>
                        <div className="bg-white dark:bg-indigo-950 rounded-lg shadow-lg p-2">
                            <table className="min-w-full table-auto mt-2 border border-gray-500 dark:border-gray-600">
                                <thead>
                                    <tr>
                                        <th colSpan="2" className="text-normal font-semibold uppercase px-2 py-1 text-yellow-500 dark:bg-indigo-900">
                                            Laporan Data Rumah Sakit
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {keys.map((key) => (
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
    );
}
