import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import ButtonDetail from "@/Components/ButtonDetail";
import Cetak from "./Cetak"
import ButtonTime from '@/Components/ButtonTime';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import TableCellMenu from "@/Components/TableCellMenu";

export default function Index({ auth, dataTable, header, totalCount, text, queryParams = {} }) {

    const headers = [
        { name: "NORM" },
        { name: "NAMA PASIEN" },
        { name: "NOMOR KUNJUNGAN" },
        { name: "TANGGAL" },
        { name: "ORDER OLEH" },
        { name: "STATUS KUNJUNGAN" },
        { name: "STATUS ORDER" },
        { name: "STATUS HASIL" },
        { name: "MENU", className: "text-center" },
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
        router.get(route('layananLab.index'), updatedParams, {
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
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Order Laboratorium {header} {totalCount} {text}</h1>
                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={9} className="px-3 py-2">
                                                <div className="flex items-center space-x-2">
                                                    <TextInput
                                                        className="w-full"
                                                        defaultValue={queryParams.search || ''}
                                                        placeholder="Cari data berdasarkan NORM, nama pasien atau nomor kunjungan"
                                                        onChange={e => onInputChange('search', e)}
                                                        onKeyPress={e => onKeyPress('search', e)}
                                                    />
                                                    <ButtonTime href={route("layananLab.filterByTime", "hariIni")} text="Hari Ini" />
                                                    <ButtonTime href={route("layananLab.filterByTime", "mingguIni")} text="Minggu Ini" />
                                                    <ButtonTime href={route("layananLab.filterByTime", "bulanIni")} text="Bulan Ini" />
                                                    <ButtonTime href={route("layananLab.filterByTime", "tahunIni")} text="Tahun Ini" />
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
                                                    <TableCell>
                                                        {data.gelarDepan} <span className='uppercase'>{data.dokter}</span> {data.gelarBelakang}
                                                    </TableCell>
                                                    <TableCell>
                                                        {data.statusKunjungan === 0 ? 'Batal' : data.statusKunjungan === 1 ? 'Sedang Dilayani' : data.statusKunjungan === 2 ? 'Selesai' : ''}
                                                    </TableCell>
                                                    <TableCell>
                                                        {data.statusOrder === 2 ? 'Sudah Final' : data.statusOrder === 1 ? 'Belum Final' : 'Batal'}
                                                    </TableCell>
                                                    <TableCell>
                                                        {data.statusHasil === 1 ? 'Sudah Final' : 'Belum Ada Hasil'}
                                                    </TableCell>
                                                    <TableCellMenu>
                                                        <ButtonDetail
                                                            href={route("layananLab.detail", { id: data.nomor })}
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

            <div className="w-full">
                <Cetak
                />
            </div>
        </AuthenticatedLayout>
    );
}
