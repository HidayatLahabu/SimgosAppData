import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";

export default function Index({ auth, ruangan, queryParams = {} }) {

    // Function to handle search input changes
    const searchFieldChanged = (field, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[field] = value;
        } else {
            delete updatedParams[field];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('ruangan.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (field, e) => {
        const value = e.target.value;
        searchFieldChanged(field, value);
    };

    // Function to handle Enter key press in search input
    const onKeyPress = (field, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(field, e.target.value);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Inventory" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Barang Ruangan</h1>
                                <div className="mb-4">
                                    <div className="flex space-x-4">
                                        <TextInput
                                            className="w-full"
                                            defaultValue={queryParams.namaRuangan || ''}
                                            placeholder="Cari ruangan"
                                            onChange={e => onInputChange('namaRuangan', e)}
                                            onKeyPress={e => onKeyPress('namaRuangan', e)}
                                        />
                                        <TextInput
                                            className="w-full"
                                            defaultValue={queryParams.namaBarang || ''}
                                            placeholder="Cari barang"
                                            onChange={e => onInputChange('namaBarang', e)}
                                            onKeyPress={e => onKeyPress('namaBarang', e)}
                                        />
                                    </div>
                                </div>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">RUANGAN</th>
                                            <th className="px-3 py-2">NAMA BARANG</th>
                                            <th className="px-3 py-2">SATUAN</th>
                                            <th className="px-3 py-2 text-center">STOCK</th>
                                            <th className="px-3 py-2">TANGGAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {ruangan.data.length > 0 ? (
                                            ruangan.data.map((ruangan, index) => (
                                                <tr key={`${ruangan.id}-${index}`} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                    <td className="px-3 py-3">{ruangan.namaRuangan}</td>
                                                    <td className="px-3 py-3">{ruangan.namaBarang}</td>
                                                    <td className="px-3 py-3">{ruangan.satuan}</td>
                                                    <td className="px-3 py-3 text-center">{ruangan.stock}</td>
                                                    <td className="px-3 py-3">{ruangan.tanggal}</td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="5" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                                <Pagination links={ruangan.links} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
