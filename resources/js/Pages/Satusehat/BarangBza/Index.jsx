import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import ButtonDetail from "@/Components/ButtonDetail";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import TableCellMenu from "@/Components/TableCellMenu";

export default function Index({ auth, dataTable, queryParams = {} }) {

    const headers = [
        { name: "ID" },
        { name: "BARANG" },
        { name: "BZA DISPLAY" },
        { name: "DOSIS KFA" },
        { name: "SATUAN DOSIS KFA" },
        { name: "DOSIS PER SATUAN" },
        { name: "SATUAN" },
        { name: "MENU", className: "text-center" },
    ];

    // Function to handle search input changes
    const searchFieldChanged = (barang, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[barang] = value;
        } else {
            delete updatedParams[barang];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('barangBza.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (barang, e) => {
        const value = e.target.value;
        searchFieldChanged(barang, value);
    };

    // Function to handle Enter key press in search input
    const onKeyPress = (barang, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(barang, e.target.value);
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="SatuSehat" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Barang</h1>
                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={9} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.barang || ''}
                                                    placeholder="Cari berdasarkan barang"
                                                    onChange={e => onInputChange('barang', e)}
                                                    onKeyPress={e => onKeyPress('barang', e)}
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
                                                    <TableCell>{data.barang}</TableCell>
                                                    <TableCell>{data.bzaDisplay}</TableCell>
                                                    <TableCell>{data.dosisKfa}</TableCell>
                                                    <TableCell>{data.satuanKfa}</TableCell>
                                                    <TableCell>{data.dosisSatuan}</TableCell>
                                                    <TableCell>{data.satuan}</TableCell>
                                                    <TableCellMenu>
                                                        <ButtonDetail
                                                            href={route("barangBza.detail", { id: data.id })}
                                                        />
                                                    </TableCellMenu>
                                                </TableRow>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="9" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
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
