import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";

export default function Index({ auth, dataTable, queryParams = {} }) {

    // Function to handle search input changes
    const searchFieldChanged = (deskripsi, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[deskripsi] = value;
        } else {
            delete updatedParams[deskripsi];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('referensi.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (name, e) => {
        const value = e.target.value;
        if (value === '') {
            // If input is cleared, reload page with queryParams and reset to page 1
            router.get(route('referensi.index'), { ...queryParams, name: '', page: 1 }, {
                preserveState: true,
                preserveScroll: true,
            });
        } else {
            // Perform search when input changes
            searchFieldChanged(name, value);
        }
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="SatuSehat" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Referensi</h1>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th colSpan={5} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.deskripsi || ''}
                                                    placeholder="Cari referensi"
                                                    onChange={e => onInputChange('deskripsi', e)}
                                                />
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr className="text-nowrap">
                                            <th className="px-3 py-2">TABLE ID</th>
                                            <th className="px-3 py-2">JENIS</th>
                                            <th className="px-3 py-2">ID</th>
                                            <th className="px-3 py-2">DESKRIPSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {dataTable.data.length > 0 ? (
                                            dataTable.data.map((dataTable, index) => (
                                                <tr key={`${dataTable.tabel_id}-${index}`} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                    <td className="px-3 py-3">{dataTable.tabel_id}</td>
                                                    <td className="px-3 py-3">{dataTable.jenis}</td>
                                                    <td className="px-3 py-3">{dataTable.id}</td>
                                                    <td className="px-3 py-3">{dataTable.deskripsi}</td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="5" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
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
