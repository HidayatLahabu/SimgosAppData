import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";

export default function Index({ auth, dataTable, queryParams = {} }) {

    // Function to handle search input changes
    const searchFieldChanged = (nama, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[nama] = value;
        } else {
            delete updatedParams[nama];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('logsAkses.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (nama, e) => {
        const value = e.target.value;
        searchFieldChanged(nama, value);
    };

    // Function to handle Enter key press in search input
    const onKeyPress = (nama, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(nama, e.target.value);
    };

    // Function to truncate JSON string
    const truncateJson = (jsonString) => {
        try {
            const jsonObject = JSON.parse(jsonString);
            const truncated = JSON.stringify(jsonObject, (key, value) => {
                if (key === 'PETUGAS_MEDIS') {
                    // Limit the number of items in PETUGAS_MEDIS array
                    value = value.slice(0, 1);
                }
                return value;
            });
            return truncated.length > 100 ? truncated.substring(0, 100) + '...' : truncated;
        } catch (e) {
            return jsonString;
        }
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Logs" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Pengguna Akses Logs</h1>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th colSpan={6} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.nama || ''}
                                                    placeholder="Cari url"
                                                    onChange={e => onInputChange('nama', e)}
                                                    onKeyPress={e => onKeyPress('nama', e)}
                                                />
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">ID</th>
                                            <th className="px-3 py-2">TANGGAL</th>
                                            <th className="px-3 py-2">PENGGUNA</th>
                                            <th className="px-3 py-2">AKSI</th>
                                            <th className="px-3 py-2">SEBELUM</th>
                                            <th className="px-3 py-2">SESUDAH</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {dataTable.data.length > 0 ? (
                                            dataTable.data.map((dataTable, index) => (
                                                <tr key={`${dataTable.id}-${index}`} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                    <td className="px-3 py-3">{dataTable.id}</td>
                                                    <td className="px-3 py-3">{dataTable.tanggal}</td>
                                                    <td className="px-3 py-3">{dataTable.nama}</td>
                                                    <td className="px-3 py-3">{dataTable.aksi}</td>
                                                    <td className="px-3 py-3 break-words max-w-xs">{truncateJson(dataTable.sebelum)}</td>
                                                    <td className="px-3 py-3 break-words max-w-xs">{truncateJson(dataTable.sesudah)}</td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="6" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
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
