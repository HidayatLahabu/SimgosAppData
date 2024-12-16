import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { formatDate } from '@/utils/formatDate';
import { formatNumber } from '@/utils/formatNumber';

export default function LaporanRl51({ auth, items, tgl_awal, tgl_akhir }) {

    // Check if items is defined and is an array
    if (!Array.isArray(items)) {
        console.error('Expected items to be an array but received:', items);
        return <div>Error: Data not available</div>;
    }


    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan RL 5.1" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">LAPORAN RL 5.1</h1>
                            <p className="text-center pb-4">
                                <strong>Periode Tanggal: </strong>{formatDate(tgl_awal)} - {formatDate(tgl_akhir)}
                            </p>
                            <div className="overflow-x-auto">
                                <table className="min-w-full table-auto w-full border border-gray-500 dark:border-gray-600">
                                    <thead>
                                        <tr className='uppercase dark:bg-indigo-700 text-yellow-500'>
                                            <th className="border border-gray-500 dark:border-gray-600 px-4 py-2 text-left">KODE RS</th>
                                            <th className="border border-gray-500 dark:border-gray-600 px-4 py-2 text-left">NAMA RUMAH SAKIT</th>
                                            <th className="border border-gray-500 dark:border-gray-600 px-4 py-2 text-left">KOTA/KABUPATEN</th>
                                            <th className="border border-gray-500 dark:border-gray-600 px-4 py-2">TAHUN</th>
                                            <th className="border border-gray-500 dark:border-gray-600 px-4 py-2 text-left">JENIS PENGUNJUNG</th>
                                            <th className="border border-gray-500 dark:border-gray-600 px-4 py-2 text-right">JUMLAH PENGUNJUNG</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {items.map((item, index) => (
                                            <tr key={index}>
                                                <td className="border border-gray-500 dark:border-gray-600 px-4 py-2">{item.KODE}</td>
                                                <td className="border border-gray-500 dark:border-gray-600 px-4 py-2">{item.NAMAINST}</td>
                                                <td className="border border-gray-500 dark:border-gray-600 px-4 py-2">{item.KOTA}</td>
                                                <td className="border border-gray-500 dark:border-gray-600 px-4 py-2 text-center">{item.TAHUN}</td>
                                                <td className="border border-gray-500 dark:border-gray-600 px-4 py-2">{item.DESKRIPSI}</td>
                                                <td className="border border-gray-500 dark:border-gray-600 px-4 py-2 text-right">{formatNumber(item.JUMLAH)} PASIEN</td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
