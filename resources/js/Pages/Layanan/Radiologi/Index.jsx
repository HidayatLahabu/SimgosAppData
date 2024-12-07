import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import ButtonDetail from "@/Components/ButtonDetail";
import Cetak from "./Cetak"
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
        router.get(route('layananRad.index'), updatedParams, {
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
            <Head title="Layanan" />

            <div className="pt-5 pb-2">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Order Radiologi {header} {totalCount} {text}</h1>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900 border border-gray-500 dark:border-gray-600">
                                    <thead className="text-sm text-nowrap font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100">
                                        <tr>
                                            <th colSpan={9} className="px-3 py-2">
                                                <div className="flex items-center space-x-2">
                                                    <TextInput
                                                        className="w-full"
                                                        defaultValue={queryParams.search || ''}
                                                        placeholder="Cari data berdasarkan nomor order, NORM, atau nama pasien"
                                                        onChange={e => onInputChange('search', e)}
                                                        onKeyPress={e => onKeyPress('search', e)}
                                                    />
                                                    <ButtonTime href={route("layananRad.filterByTime", "hariIni")} text="Hari Ini" />
                                                    <ButtonTime href={route("layananRad.filterByTime", "mingguIni")} text="Minggu Ini" />
                                                    <ButtonTime href={route("layananRad.filterByTime", "bulanIni")} text="Bulan Ini" />
                                                    <ButtonTime href={route("layananRad.filterByTime", "tahunIni")} text="Tahun Ini" />
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500">
                                        <tr>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">NORM</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">NAMA PASIEN</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">NOMOR ORDER</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">TANGGAL</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">ORDER OLEH</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">STATUS<br />KUNJUNGAN</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">STATUS<br />ORDER</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600">STATUS<br />HASIL</th>
                                            <th className="px-3 py-2 border border-gray-500 dark:border-gray-600 text-center">MENU</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {dataTable.data.length > 0 ? (
                                            dataTable.data.map((data, index) => (
                                                <tr key={`${data.nomor}-${index}`}
                                                    className={`hover:bg-indigo-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 ${index % 2 === 0
                                                        ? 'bg-gray-50 dark:bg-indigo-950'
                                                        : 'bg-gray-50 dark:bg-indigo-950'
                                                        }`}>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{data.norm}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600 uppercase">{data.nama}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{data.nomor}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{data.tanggal}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{data.gelarDepan} <span className='uppercase'>{data.dokter}</span>  {data.gelarBelakang}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{data.statusKunjungan === 0 ? 'Batal' : data.statusKunjungan === 1 ? 'Sedang Dilayani' : data.statusKunjungan === 2 ? 'Selesai' : ''}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{data.statusOrder === 0 ? 'Batal' : data.statusOrder === 1 ? 'Belum Final' : data.statusOrder === 2 ? 'Sudah Final' : ''}</td>
                                                    <td className="px-3 py-3 border border-gray-500 dark:border-gray-600">{data.statusHasil === 1 ? 'Belum Final' : data.statusHasil === 2 ? 'Sudah Final' : 'Belum Ada Hasil'}</td>
                                                    <td className="px-1 py-1 text-center flex items-center justify-center space-x-1">
                                                        <ButtonDetail
                                                            href={route("layananRad.detail", { id: data.nomor })}
                                                        />
                                                    </td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
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

            <div className="w-full">
                <Cetak
                />
            </div>
        </AuthenticatedLayout>
    );
}
