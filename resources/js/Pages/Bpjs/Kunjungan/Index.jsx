import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/Input/TextInput";
import Pagination from "@/Components/Pagination";
import ButtonDetail from "@/Components/Button/ButtonDetail";
import ButtonTime from '@/Components/Button/ButtonTime';
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import TableCellMenu from "@/Components/Table/TableCellMenu";

export default function Index({ auth, dataTable, header, totalCount, queryParams = {} }) {

    const headers = [
        { name: "NORM", className: "w-[7%]" },
        { name: "NAMA PASIEN" },
        { name: "SEP", className: "w-[14%]" },
        { name: "TANGGAL SEP", className: "w-[12%]" },
        { name: "RUJUKAN", className: "w-[14%]" },
        { name: "TANGGAL RUJUKAN", className: "w-[12%]" },
        { name: "MENU", className: "text-center w-[7%]" },
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
        router.get(route('kunjunganBpjs.index'), updatedParams, {
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
            <Head title="BPJS" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Kunjungan BPJS {header} {totalCount} Pasien</h1>
                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={7} className="px-3 py-2">
                                                <div className="flex items-center space-x-2">
                                                    <TextInput
                                                        className="w-full"
                                                        defaultValue={queryParams.search || ''}
                                                        placeholder="Cari data berdasarkan NORM, nama pasien, SEP atau rujukan, "
                                                        onChange={e => onInputChange('search', e)}
                                                        onKeyPress={e => onKeyPress('search', e)}
                                                    />
                                                    <ButtonTime href={route("kunjunganBpjs.filterByTime", "hariIni")} text="Hari Ini" />
                                                    <ButtonTime href={route("kunjunganBpjs.filterByTime", "mingguIni")} text="Minggu Ini" />
                                                    <ButtonTime href={route("kunjunganBpjs.filterByTime", "bulanIni")} text="Bulan Ini" />
                                                    <ButtonTime href={route("kunjunganBpjs.filterByTime", "tahunIni")} text="Tahun Ini" />
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
                                                <TableRow key={data.noSEP} isEven={index % 2 === 0}>
                                                    <TableCell>{data.norm}</TableCell>
                                                    <TableCell className='uppercase'>{data.nama}</TableCell>
                                                    <TableCell>{data.noSEP}</TableCell>
                                                    <TableCell>{data.tglSEP}</TableCell>
                                                    <TableCell>{data.noRujukan}</TableCell>
                                                    <TableCell>{data.tglRujukan}</TableCell>
                                                    <TableCellMenu>
                                                        <ButtonDetail
                                                            href={route("kunjunganBpjs.detail", { id: data.noSEP })}
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
