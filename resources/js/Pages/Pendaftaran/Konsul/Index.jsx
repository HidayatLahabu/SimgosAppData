import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import ButtonDetail from "@/Components/ButtonDetail";
import ButtonTime from '@/Components/ButtonTime';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import Card from "@/Components/Card";
import Cetak from "./Cetak"
import TableCellMenu from "@/Components/TableCellMenu";

export default function Index({ auth, dataTable, header, totalCount, rataRata, ruangan, queryParams = {} }) {

    const headers = [
        { name: "NORM" },
        { name: "NAMA PASIEN" },
        { name: "NOMOR" },
        { name: "TANGGAL" },
        { name: "RUANGAN ASAL" },
        { name: "RUANGAN TUJUAN" },
        { name: "STATUS" },
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
        router.get(route('konsul.index'), updatedParams, {
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
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Konsul {header} {totalCount} Pasien</h1>
                                <div className="flex flex-wrap gap-4 justify-between mb-4">
                                    <Card title="RATA-RATA PER HARI" value={rataRata.rata_rata_per_hari} />
                                    <Card title="RATA-RATA PER MINGGU" value={rataRata.rata_rata_per_minggu} />
                                    <Card title="RATA-RATA PER BULAN" value={rataRata.rata_rata_per_bulan} />
                                    <Card title="RATA-RATA PER TAHUN" value={rataRata.rata_rata_per_tahun} />
                                </div>
                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={8} className="px-3 py-2">
                                                <div className="flex items-center space-x-2">
                                                    <TextInput
                                                        className="w-full"
                                                        defaultValue={queryParams.search || ''}
                                                        placeholder="Cari data berdasarkan NORM, nama pasien atau nomor konsul"
                                                        onChange={e => onInputChange('search', e)}
                                                        onKeyPress={e => onKeyPress('search', e)}
                                                    />
                                                    <ButtonTime href={route("konsul.filterByTime", "hariIni")} text="Hari Ini" />
                                                    <ButtonTime href={route("konsul.filterByTime", "mingguIni")} text="Minggu Ini" />
                                                    <ButtonTime href={route("konsul.filterByTime", "bulanIni")} text="Bulan Ini" />
                                                    <ButtonTime href={route("konsul.filterByTime", "tahunIni")} text="Tahun Ini" />
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
                                                    <TableCell>{data.asal}</TableCell>
                                                    <TableCell>{data.tujuan}</TableCell>
                                                    <TableCell>
                                                        {data.status === 0 ? 'Batal' : data.status === 1 ? 'Belum Diterima' : 'Sudah Diterima'}
                                                    </TableCell>
                                                    <TableCellMenu>
                                                        <ButtonDetail
                                                            href={route("konsul.detail", { id: data.nomor })}
                                                        />
                                                    </TableCellMenu>
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

            <div className="w-full">
                <Cetak ruangan={ruangan} queryParams={queryParams || {}} />
            </div>

        </AuthenticatedLayout>
    );
}

