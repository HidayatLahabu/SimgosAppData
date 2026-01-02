import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/Input/TextInput";
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import TableCellMenu from "@/Components/Table/TableCellMenu";
import ButtonDetail from "@/Components/Button/ButtonDetail";

export default function Index({ auth, dataTable, queryParams = {} }) {

    const headers = [
        { name: "NORM" },
        { name: "NAMA PASIEN" },
        { name: "NOMOR ORDER" },
        { name: "TANGGAL" },
        { name: "TUJUAN" },
        { name: "ORDER OLEH" },
        { name: "MENU" },
    ];

    // Function to handle search input changes
    const searchFieldChanged = (nama, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[nama] = value;
        } else {
            delete updatedParams[nama];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('toolsResep.index'), updatedParams, {
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

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Tools" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Order Resep</h1>
                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={7} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.nama || ''}
                                                    placeholder="Cari pasien"
                                                    onChange={e => onInputChange('nama', e)}
                                                    onKeyPress={e => onKeyPress('nama', e)}
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
                                                <TableRow key={data.nomor} isEven={index % 2 === 0}>
                                                    <TableCell>{data.norm}</TableCell>
                                                    <TableCell className='uppercase'>{data.nama}</TableCell>
                                                    <TableCell>{data.nomor}</TableCell>
                                                    <TableCell>{data.tanggal}</TableCell>
                                                    <TableCell>{data.ruangan}</TableCell>
                                                    <TableCell>{data.orderOleh}</TableCell>
                                                    <TableCellMenu>
                                                        <ButtonDetail
                                                            href={route("toolsResep.edit", { id: data.nomor })}
                                                        />
                                                    </TableCellMenu>
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
