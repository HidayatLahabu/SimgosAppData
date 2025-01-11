import React from 'react';
import { router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import { formatDate } from '@/utils/formatDate';

export default function Harian({ harian, queryParams = {} }) {

    const headers = [
        { name: "TANGGAL", className: "w-[9%]" },
        { name: "JENIS KUNJUNGAN" },
        { name: "SUB UNIT" },
        { name: "KUNJUNGAN", className: "text-right w-[10%]" },
        { name: "LAST UPDATED" },
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
        router.get(route('informasiKunjungan.index'), updatedParams, {
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
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:pl-5 sm:pr-2 lg:pl-5 pr-2">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">
                                Kunjungan Rawat Jalan Harian
                            </h1>

                            <Table>

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
                                    {harian.data.length > 0 ? (
                                        harian.data.map((data, index) => (
                                            <TableRow key={data.lastUpdated} isEven={index % 2 === 0}>
                                                <TableCell>{formatDate(data.tanggal)}</TableCell>
                                                <TableCell>{data.jenisKunjungan}</TableCell>
                                                <TableCell>{data.subUnit}</TableCell>
                                                <TableCell className='text-right'>{data.jumlah}</TableCell>
                                                <TableCell>{data.lastUpdated}</TableCell>
                                            </TableRow>
                                        ))
                                    ) : (
                                        <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                            <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                        </tr>
                                    )}
                                </tbody>
                            </Table>
                            <Pagination links={harian.links} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
