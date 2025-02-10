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
import CardMenu from "@/Components/Card/CardMenu";

export default function Index({ auth, dataTable, header, totalCount, rataRata, queryParams = {} }) {

    const headers = [
        { name: "NOMOR", className: "w-[9%]" },
        { name: "NORM", className: "w-[7%]" },
        { name: "NAMA PASIEN" },
        { name: "TANGGAL", className: "text-center w-[12%]" },
        { name: "PENJAMIN", className: "w-[15%]" },
        { name: "STATUS", className: "text-center w-[7%]" },
        { name: "MENU", className: "text-center w-[5%]" },
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
        router.get(route('pendaftaran.index'), updatedParams, {
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
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">
                                    Data Pendaftaran {header} {totalCount} Pasien
                                </h1>
                                <div className="flex flex-wrap gap-4 justify-between mb-4">
                                    <CardMenu title="RATA-RATA PER HARI" value={rataRata.rata_rata_per_hari} />
                                    <CardMenu title="RATA-RATA PER MINGGU" value={rataRata.rata_rata_per_minggu} />
                                    <CardMenu title="RATA-RATA PER BULAN" value={rataRata.rata_rata_per_bulan} />
                                    <CardMenu title="RATA-RATA PER TAHUN" value={rataRata.rata_rata_per_tahun} />
                                </div>
                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={7} className="px-3 py-2">
                                                <div className="flex items-center space-x-2">
                                                    <TextInput
                                                        className="flex-1"
                                                        defaultValue={queryParams.search || ''}
                                                        placeholder="Cari data berdasarkan NORM, nama pasien atau nomor pendaftaran"
                                                        onChange={e => onInputChange('search', e)}
                                                        onKeyPress={e => onKeyPress('search', e)}
                                                    />
                                                    <ButtonTime href={route("pendaftaran.filterByTime", "hariIni")} text="Hari Ini" />
                                                    <ButtonTime href={route("pendaftaran.filterByTime", "mingguIni")} text="Minggu Ini" />
                                                    <ButtonTime href={route("pendaftaran.filterByTime", "bulanIni")} text="Bulan Ini" />
                                                    <ButtonTime href={route("pendaftaran.filterByTime", "tahunIni")} text="Tahun Ini" />
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
                                                    <TableCell>{data.nomor}</TableCell>
                                                    <TableCell>{data.norm}</TableCell>
                                                    <TableCell className='uppercase'>{data.nama}</TableCell>
                                                    <TableCell className='text-center'>{data.tanggal}</TableCell>
                                                    <TableCell>{data.penjamin}</TableCell>
                                                    <TableCell className='text-center'>
                                                        {data.status === 0 ? 'Batal' : data.status === 1 ? 'Aktif' : 'Selesai'}
                                                    </TableCell>
                                                    <TableCellMenu>
                                                        <ButtonDetail
                                                            href={route("pendaftaran.detail", { id: data.nomor })}
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
