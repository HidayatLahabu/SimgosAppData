import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import ButtonTime from '@/Components/ButtonTime';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function Index({ auth, dataTable, antrianData, filterKunjungan, header, headerKunjungan, queryParams = {} }) {

    const headers = [
        { name: "NORM" },
        { name: "NAMA PASIEN" },
        { name: "NOMOR ANTRIAN" },
        { name: "TANGGAL" },
        { name: "RUANGAN TUJUAN" },
        { name: "NOMOR URUT" },
        { name: "PENDAFTARAN" },
        { name: "STATUS" },
    ];

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
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Antrian Ruangan {header} {filterKunjungan && headerKunjungan}</h1>

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
                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={8} className="px-3 py-2">
                                                <div className="flex items-center space-x-2">
                                                    <TextInput
                                                        className="w-full"
                                                        defaultValue={queryParams.search || ''}
                                                        placeholder="Cari data berdasarkan NORM, nama pasien atau nomor antrian"
                                                        onChange={e => onInputChange('search', e)}
                                                        onKeyPress={e => onKeyPress('search', e)}
                                                    />
                                                    <ButtonTime href={route("antrian.filterByKunjungan", "rajal")} text="Rawat Jalan" />
                                                    <ButtonTime href={route("antrian.filterByKunjungan", "ranap")} text="Rawat Inap" />
                                                    <ButtonTime href={route("antrian.filterByKunjungan", "darurat")} text="Rawat Darurat" />
                                                </div>
                                            </th>
                                        </tr>
                                    </TableHeader>
                                    <TableHeader>
                                        <tr>
                                            {headers.map((header, index) => (
                                                <TableHeaderCell key={index} className={header.className || ""}>
                                                    {header.name}
                                                </TableHeaderCell>
                                            ))}
                                        </tr>
                                    </TableHeader>
                                    <tbody>
                                        {dataTable.data.length > 0 ? (
                                            dataTable.data.map((data, index) => (
                                                <TableRow key={data.nomor} isEven={index % 2 === 0}>
                                                    <TableCell>{data.norm}</TableCell>
                                                    <TableCell className='uppercase'>{data.nama}</TableCell>
                                                    <TableCell>{data.nomor}</TableCell>
                                                    <TableCell>{data.tanggal}</TableCell>
                                                    <TableCell>{data.ruangan}</TableCell>
                                                    <TableCell>{data.urut}</TableCell>
                                                    <TableCell>{data.pendaftaran}</TableCell>
                                                    <TableCell>{data.status === 0 ? 'Batal' : data.status === 1 ? 'Belum Diterima' : 'Sudah Diterima'}</TableCell>
                                                </TableRow>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                            </tr>
                                        )}
                                    </tbody>
                                </Table>
                                <Pagination links={dataTable.links} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

