import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import { formatDate } from '@/utils/formatDate';
import ButtonTime from '@/Components/ButtonTime';

export default function Index({ auth, dataTable, reservasiData, filter, header, queryParams = {} }) {

    // Function to handle search input changes
    const searchFieldChanged = (search, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[search] = value;
        } else {
            delete updatedParams[search];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('antrian.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (search, e) => {
        const value = e.target.value;
        searchFieldChanged(search, value);
    };

    // Function to handle Enter key press in search input
    const onKeyPress = (search, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(search, e.target.value);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Pendaftaran" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Reservasi {header}</h1>

                                <div className="flex flex-wrap gap-4 justify-between mb-4">
                                    <a
                                        href={route("reservasi.index")}
                                        className="flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block"
                                    >
                                        <h2 className="text-lg font-bold text-gray-700 dark:text-yellow-400">JUMLAH DATA RESERVASI</h2>
                                        <p className="text-2xl font-semibold text-indigo-600 dark:text-white mt-2">
                                            {reservasiData.total_reservasi} PASIEN
                                        </p>
                                    </a>
                                    <a
                                        href={route("reservasi.filterByStatus", "batal")}
                                        className="flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block"
                                    >
                                        <h2 className="text-lg font-bold text-gray-700 dark:text-yellow-400">BATAL RESERVASI</h2>
                                        <p className="text-2xl font-semibold text-indigo-600 dark:text-white mt-2">
                                            {reservasiData.total_batal_reservasi} PASIEN
                                        </p>
                                    </a>
                                    <a
                                        href={route("reservasi.filterByStatus", "reservasi")}
                                        className="flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block"
                                    >
                                        <h2 className="text-lg font-bold text-gray-700 dark:text-yellow-400">PROSES RESERVASI</h2>
                                        <p className="text-2xl font-semibold text-indigo-600 dark:text-white mt-2">
                                            {reservasiData.total_proses_reservasi} PASIEN
                                        </p>
                                    </a>
                                    <a
                                        href={route("reservasi.filterByStatus", "selesai")}
                                        className="flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block"
                                    >
                                        <h2 className="text-lg font-bold text-gray-700 dark:text-yellow-400">SELESAI RESERVASI</h2>
                                        <p className="text-2xl font-semibold text-indigo-600 dark:text-white mt-2">
                                            {reservasiData.total_selesai_reservasi} PASIEN
                                        </p>
                                    </a>
                                </div>

                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm text-nowrap font-bold text-gray-700 bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th colSpan={7} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.search || ''}
                                                    placeholder="Cari data berdasarkan nomor antrian, NORM, atau nama pasien"
                                                    onChange={e => onInputChange('search', e)}
                                                    onKeyPress={e => onKeyPress('search', e)}
                                                />
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-400 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">NOMOR</th>
                                            <th className="px-3 py-2">TANGGAL</th>
                                            <th className="px-3 py-2">ATAS NAMA</th>
                                            <th className="px-3 py-2">RUANGAN</th>
                                            <th className="px-3 py-2">KAMAR</th>
                                            <th className="px-3 py-2">TEMPAT TIDUR</th>
                                            {filter ? (
                                                <th className="px-3 py-2">NOMOR KONTAK</th>
                                            ) : (
                                                <th className="px-3 py-2">STATUS</th>
                                            )}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {dataTable.data.length > 0 ? (
                                            dataTable.data.map((dataTable, index) => (
                                                <tr key={`${dataTable.nomor}-${index}`}
                                                    className={`hover:bg-indigo-100 dark:hover:bg-indigo-800 ${index % 2 === 0
                                                        ? 'bg-gray-50 dark:bg-indigo-950'
                                                        : 'bg-gray-50 dark:bg-indigo-950'
                                                        }`}>
                                                    <td className="px-3 py-3">{dataTable.nomor}</td>
                                                    <td className="px-3 py-3">{dataTable.tanggal}</td>
                                                    <td className="px-3 py-3 uppercase">{dataTable.pasien}</td>
                                                    <td className="px-3 py-3">{dataTable.ruangan}</td>
                                                    <td className="px-3 py-3">{dataTable.kamar}</td>
                                                    <td className="px-3 py-3">{dataTable.tempatTidur}</td>
                                                    {filter ? (
                                                        <td className="px-3 py-3">{dataTable.kontak}</td>
                                                    ) : (
                                                        <td className="px-3 py-3">{dataTable.status === 0 ? 'Batal Reservasi' : dataTable.status === 1 ? 'Reservasi' : 'Selesai'}</td>
                                                    )}
                                                </tr>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="7" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                                <Pagination links={dataTable.links} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

