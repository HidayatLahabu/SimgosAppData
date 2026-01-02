import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/Input/TextInput";
import Pagination from "@/Components/Pagination";
import { formatNumber } from "@/utils/formatNumber";
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";

export default function Index({ auth, dataTable, queryParams = {} }) {

    const headers = [
        { name: "ID BARANG" },
        { name: "RUANGAN" },
        { name: "ID BARANG RUANGAN" },
        { name: "NAMA BARANG" },
        { name: "SATUAN" },
        { name: "STOCK", className: "text-right" },
        { name: "TANGGAL" },
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
        router.get(route('barangRuangan.index'), updatedParams, {
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
            <Head title="Inventory" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Barang Ruangan</h1>
                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={7} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.search || ''}
                                                    placeholder="Cari nama barang dan ruangan"
                                                    onChange={e => onInputChange('search', e)}
                                                    onKeyPress={e => onKeyPress('search', e)}
                                                />
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
                                                <TableRow key={data.id} isEven={index % 2 === 0}>
                                                    <TableCell>{data.id}</TableCell>
                                                    <TableCell className='uppercase'>{data.namaRuangan}</TableCell>
                                                    <TableCell>{data.id_ruangan}</TableCell>
                                                    <TableCell className='uppercase'>{data.namaBarang}</TableCell>
                                                    <TableCell>{data.satuan}</TableCell>
                                                    <TableCell className='text-right'>{formatNumber(data.stock)}</TableCell>
                                                    <TableCell>{data.tanggal}</TableCell>
                                                </TableRow>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="7" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
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
