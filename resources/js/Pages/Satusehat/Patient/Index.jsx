import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import ButtonDetail from "@/Components/ButtonDetail";
import ButtonTime from '@/Components/ButtonTime';

export default function Index({ auth, dataTable, header, totalCount, text, queryParams = {} }) {

    // Function to handle search input changes
    const searchFieldChanged = (search, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[search] = value;
        } else {
            delete updatedParams[search];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('patient.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (search, e) => {
        const value = e.target.value;
        if (value === '') {
            // If input is cleared, reload page with queryParams and reset to page 1
            router.get(route('patient.index'), { ...queryParams, search: '', page: 1 }, {
                preserveState: true,
                preserveScroll: true,
            });
        } else {
            // Perform search when input changes
            searchFieldChanged(search, value);
        }
    };

    // Function to handle Enter key press in search input
    const onKeyPress = (search, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(search, e.target.value);
    };

    // Function to shuffle the digits of a 16-digit NIK
    const shuffleNumber = (number) => {
        const nikArray = number.split('');
        for (let i = nikArray.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [nikArray[i], nikArray[j]] = [nikArray[j], nikArray[i]];
        }
        return nikArray.join('');
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
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Patient {header} {totalCount} {text}</h1>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900 border border-gray-500 dark:border-gray-600">
                                    <thead className="text-sm text-nowrap font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500">
                                        <tr>
                                            <th colSpan={8} className="px-3 py-2">
                                                <div className="flex items-center space-x-2">
                                                    <TextInput
                                                        className="w-full"
                                                        defaultValue={queryParams.search || ''}
                                                        placeholder="Cari patient berdasarkan ID, nama, ref ID, atau NIK"
                                                        onChange={e => onInputChange('search', e)}
                                                        onKeyPress={e => onKeyPress('search', e)}
                                                    />
                                                    <ButtonTime href={route("patient.filterByTime", "hariIni")} text="Hari Ini" />
                                                    <ButtonTime href={route("patient.filterByTime", "mingguIni")} text="Minggu Ini" />
                                                    <ButtonTime href={route("patient.filterByTime", "bulanIni")} text="Bulan Ini" />
                                                    <ButtonTime href={route("patient.filterByTime", "tahunIni")} text="Tahun Ini" />
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500">
                                        <tr>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">ID</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">NAMA</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">REF ID</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">NIK</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">GET DATE</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600 text-center">MENU</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {dataTable.data.length > 0 ? (
                                            dataTable.data.map((data, index) => (
                                                <tr key={`${data.refId}-${index}`}
                                                    className={`hover:bg-indigo-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 ${index % 2 === 0
                                                        ? 'bg-gray-50 dark:bg-indigo-950'
                                                        : 'bg-gray-50 dark:bg-indigo-950'
                                                        }`}>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{data.id}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{data.name}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{data.refId}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{data.nik}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{data.getDate}</td>
                                                    <td className="px-1 py-1 text-center flex items-center justify-center space-x-1">
                                                        <ButtonDetail
                                                            href={route("patient.detail", { id: data.refId })}
                                                        />
                                                    </td>
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
