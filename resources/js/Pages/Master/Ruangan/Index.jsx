import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function Index({ auth, dataTable, queryParams = {} }) {

    const headers = [
        { name: "ID", className: "w-[7%]" },
        { name: "JENIS ID", className: "w-[7%]" },
        { name: "JENIS DESKRIPSI" },
        { name: "JENIS KUNJUNGAN", className: "w-[7%]" },
        { name: "NAMA RUANGAN" },
    ];

    // Function to handle search input changes
    const searchFieldChanged = (deskripsi, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[deskripsi] = value;
        } else {
            delete updatedParams[deskripsi];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('ruangan.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (name, e) => {
        const value = e.target.value;
        if (value === '') {
            // If input is cleared, reload page with queryParams and reset to page 1
            router.get(route('ruangan.index'), { ...queryParams, name: '', page: 1 }, {
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
            <Head title="Master" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Ruangan</h1>
                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={6} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.deskripsi || ''}
                                                    placeholder="Cari ruangan"
                                                    onChange={e => onInputChange('deskripsi', e)}
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
                                                    <TableCell>{data.jenisId}</TableCell>
                                                    <TableCell>{data.jenisNama}</TableCell>
                                                    <TableCell>{data.jenisKunjungan}</TableCell>
                                                    <TableCell>{data.namaRuangan}</TableCell>
                                                </TableRow>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="6" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
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
