import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";

export default function Index({ auth, dataTable, queryParams = {} }) {

    // Function to handle search input changes
    const searchFieldChanged = (name, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[name] = value;
        } else {
            delete updatedParams[name];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('patient.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (name, e) => {
        const value = e.target.value;
        if (value === '') {
            // If input is cleared, reload page with queryParams and reset to page 1
            router.get(route('patient.index'), { ...queryParams, name: '', page: 1 }, {
                preserveState: true,
                preserveScroll: true,
            });
        } else {
            // Perform search when input changes
            searchFieldChanged(name, value);
        }
    };

    // Function to handle Enter key press in search input
    const onKeyPress = (name, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(name, e.target.value);
    };

    // Function to shuffle the digits of a 16-digit NIK
    const shuffleNumber = (number) => {
        // Convert the NIK to an array of characters
        const nikArray = number.split('');
        // Shuffle the array
        for (let i = nikArray.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [nikArray[i], nikArray[j]] = [nikArray[j], nikArray[i]];
        }
        // Join the array back into a string
        return nikArray.join('');
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="SatuSehat" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Patient</h1>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th colSpan={5} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.name || ''}
                                                    placeholder="Cari pasien"
                                                    onChange={e => onInputChange('name', e)}
                                                    onKeyPress={e => onKeyPress('name', e)}
                                                />
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">ID</th>
                                            <th className="px-3 py-2">NAMA</th>
                                            <th className="px-3 py-2">REF ID</th>
                                            <th className="px-3 py-2">NIK</th>
                                            <th className="px-3 py-2">GET DATE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {dataTable.data.length > 0 ? (
                                            dataTable.data.map((dataTable, index) => (
                                                <tr key={`${dataTable.id}-${index}`} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                    <td className="px-3 py-3">{dataTable.id}</td>
                                                    <td className="px-3 py-3">{dataTable.name}</td>
                                                    <td className="px-3 py-3">{dataTable.refId}</td>
                                                    {/* <td className="px-3 py-3">{patient.nik}</td> */}
                                                    <td className="px-3 py-3">{shuffleNumber(dataTable.nik)}</td>
                                                    <td className="px-3 py-3">{dataTable.getDate}</td>
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
