import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/Input/TextInput";
import Pagination from "@/Components/Pagination";
import ButtonDetail from "@/Components/Button/ButtonDetail";
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import TableCellMenu from "@/Components/Table/TableCellMenu";

export default function Index({ auth, dataTable, header, totalCount, text, queryParams = {} }) {

    const headers = [
        { name: "TANGGAL", className: "w-[7%]" },
        { name: "ID HASIL", className: "w-[6%]" },
        { name: "KUNJUNGAN", className: "text-center w-[12%]" },
        { name: "NORM", className: "w-[6%]" },
        { name: "NAMA PASIEN" },
        { name: "TINDAKAN", className: "text-wrap" },
        { name: "KLINIS", className: "text-wrap" },
        { name: "KESAN", className: "text-wrap" },
        { name: "USUL", className: "text-wrap" },
        { name: "HASIL", className: "text-wrap" },
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
        router.get(route('layananRad.hasil'), updatedParams, {
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
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Hasil Radiologi {header} {totalCount} {text}</h1>
                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={10} className="px-3 py-2">
                                                <div className="flex items-center space-x-2">
                                                    <TextInput
                                                        className="w-full"
                                                        defaultValue={queryParams.search || ''}
                                                        placeholder="Cari data berdasarkan id hasil, kunjungan, NORM, atau nama pasien"
                                                        onChange={e => onInputChange('search', e)}
                                                        onKeyPress={e => onKeyPress('search', e)}
                                                    />
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
                                                <TableRow key={data.idHasil} isEven={index % 2 === 0}>
                                                    <TableCell>{data.tanggal}</TableCell>
                                                    <TableCell>{data.idHasil}</TableCell>
                                                    <TableCell>{data.kunjungan}</TableCell>
                                                    <TableCell>{data.norm}</TableCell>
                                                    <TableCell className='uppercase'>{data.namaPasien}</TableCell>
                                                    <TableCell>{data.tindakan}</TableCell>
                                                    <TableCell>{data.klinis}</TableCell>
                                                    <TableCell>{data.kesan}</TableCell>
                                                    <TableCell>{data.usul}</TableCell>
                                                </TableRow>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="10" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
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
