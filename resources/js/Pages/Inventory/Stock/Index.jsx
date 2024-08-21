import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import ButtonDetail from "@/Components/ButtonDetail";

export default function Index({ auth, stock, queryParams = {} }) {

    // Function to handle search input changes
    const searchFieldChanged = (namaRuangan, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[namaRuangan] = value;
        } else {
            delete updatedParams[namaRuangan];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('stockBarang.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (namaRuangan, e) => {
        const value = e.target.value;
        searchFieldChanged(namaRuangan, value);
    };

    // Function to handle Enter key press in search input
    const onKeyPress = (namaRuangan, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(namaRuangan, e.target.value);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Inventory" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Stock Opname</h1>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th colSpan={5} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.namaRuangan || ''}
                                                    placeholder="Cari ruangan"
                                                    onChange={e => onInputChange('namaRuangan', e)}
                                                    onKeyPress={e => onKeyPress('namaRuangan', e)}
                                                />
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">RUANGAN</th>
                                            <th className="px-3 py-2">TANGGAL OPNAME</th>
                                            <th className="px-3 py-2">TANGGAL INPUT</th>
                                            <th className="px-3 py-2">STATUS</th>
                                            <th className="px-3 py-2 text-center">MENU</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {stock.data.length > 0 ? (
                                            stock.data.map((stock, index) => (
                                                <tr key={`${stock.id}-${index}`} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                    <td className="px-3 py-3">{stock.namaRuangan}</td>
                                                    <td className="px-3 py-3">{stock.tanggal}</td>
                                                    <td className="px-3 py-3">{stock.dibuat}</td>
                                                    <td className="px-3 py-3">{stock.status}</td>
                                                    <td className="px-1 py-1 text-center flex items-center justify-center space-x-1">
                                                        <ButtonDetail
                                                            href={route("stockBarang.list", { id: stock.id })}
                                                        />
                                                    </td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="5" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                                <Pagination links={stock.links} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
