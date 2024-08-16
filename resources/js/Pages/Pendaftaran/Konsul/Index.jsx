import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import { formatDate } from '@/utils/formatDate';

export default function Index({ auth, konsul, queryParams = {} }) {

    // Function to handle search input changes
    const searchFieldChanged = (nama, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[nama] = value;
        } else {
            delete updatedParams[nama];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('konsul.index'), updatedParams, {
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
            <Head title="Master" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Konsul</h1>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th colSpan={6} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.nama || ''}
                                                    placeholder="Cari pasien"
                                                    onChange={e => onInputChange('nama', e)}
                                                    onKeyPress={e => onKeyPress('nama', e)}
                                                />
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">NOMOR</th>
                                            <th className="px-3 py-2">NAMA</th>
                                            <th className="px-3 py-2">TANGGAL</th>
                                            <th className="px-3 py-2">RUANGAN ASAL</th>
                                            <th className="px-3 py-2">RUANGAN TUJUAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {konsul.data.length > 0 ? (
                                            konsul.data.map((konsul, index) => (
                                                <tr key={`${konsul.nomor}-${index}`} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                    <td className="px-3 py-3">{konsul.nomor}</td>
                                                    <td className="px-3 py-3">{konsul.nama}</td>
                                                    <td className="px-3 py-3">{konsul.tanggal}</td>
                                                    <td className="px-3 py-3">{konsul.asal}</td>
                                                    <td className="px-3 py-3">{konsul.tujuan}</td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="6" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                                <Pagination links={konsul.links} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

