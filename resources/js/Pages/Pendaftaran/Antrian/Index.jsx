import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import { formatDate } from '@/utils/formatDate';
import ButtonTime from '@/Components/ButtonTime';

export default function Index({ auth, dataTable, antrianData, filter, header, queryParams = {} }) {

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
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Antrian Ruangan {header}</h1>

                                <div className="flex flex-wrap gap-4 justify-between mb-4">
                                    <a
                                        href={route("antrian.index")}
                                        className="flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block"
                                    >
                                        <h2 className="text-lg font-bold text-gray-700 dark:text-yellow-400">JUMLAH DATA ANTRIAN</h2>
                                        <p className="text-2xl font-semibold text-indigo-600 dark:text-white mt-2">
                                            {antrianData.total_antrian} PASIEN
                                        </p>
                                    </a>
                                    <a
                                        href={route("antrian.filterByStatus", "batal")}
                                        className="flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block"
                                    >
                                        <h2 className="text-lg font-bold text-gray-700 dark:text-yellow-400">ANTRIAN BATAL</h2>
                                        <p className="text-2xl font-semibold text-indigo-600 dark:text-white mt-2">
                                            {antrianData.total_batal} PASIEN
                                        </p>
                                    </a>
                                    <a
                                        href={route("antrian.filterByStatus", "belumDiterima")}
                                        className="flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block"
                                    >
                                        <h2 className="text-lg font-bold text-gray-700 dark:text-yellow-400">ANTRIAN BELUM DITERIMA</h2>
                                        <p className="text-2xl font-semibold text-indigo-600 dark:text-white mt-2">
                                            {antrianData.total_belum_diterima} PASIEN
                                        </p>
                                    </a>
                                    <a
                                        href={route("antrian.filterByStatus", "diterima")}
                                        className="flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block"
                                    >
                                        <h2 className="text-lg font-bold text-gray-700 dark:text-yellow-400">ANTRIAN  SUDAH DITERIMA</h2>
                                        <p className="text-2xl font-semibold text-indigo-600 dark:text-white mt-2">
                                            {antrianData.total_diterima} PASIEN
                                        </p>
                                    </a>
                                </div>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900 border border-gray-500 dark:border-gray-600">
                                    <thead className="text-sm text-nowrap font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100">
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
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500">
                                        <tr>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">ID ANTRIAN</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">NORM</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">NAMA PASIEN</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">TANGGAL</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">RUANGAN</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">NO URUT</th>
                                            {filter ? (
                                                <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">PENDAFTARAN</th>
                                            ) : (
                                                <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">STATUS</th>
                                            )}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {dataTable.data.length > 0 ? (
                                            dataTable.data.map((dataTable, index) => (
                                                <tr key={`${dataTable.nomor}-${index}`}
                                                    className={`hover:bg-indigo-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 ${index % 2 === 0
                                                        ? 'bg-gray-50 dark:bg-indigo-950'
                                                        : 'bg-gray-50 dark:bg-indigo-950'
                                                        }`}>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{dataTable.nomor}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{dataTable.norm}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600 uppercase">{dataTable.nama}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{formatDate(dataTable.tanggal)}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{dataTable.ruangan}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{dataTable.urut}</td>
                                                    {filter ? (
                                                        <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{dataTable.pendaftaran}</td>
                                                    ) : (
                                                        <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{dataTable.status === 0 ? 'Batal' : dataTable.status === 1 ? 'Belum Diterima' : 'Diterima'}</td>
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

