import React, { useState } from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import { formatRibuan } from '@/utils/formatRibuan';

export default function Mingguan({ mingguan }) {

    const headers = [
        { name: "TAHUN", className: "w-[9%]" },
        { name: "MINGGU KE" },
        { name: "SUB UNIT" },
        { name: "KUNJUNGAN", className: "text-right w-[10%]" },
        { name: "LAST UPDATED", className: "w-[25%]" },
    ];

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:pl-2 sm:pr-5 lg:pl-2 lg:pr-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">
                                Kunjungan Instalasi Penunjang Mingguan
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
                                    {mingguan.data.length > 0 ? (
                                        mingguan.data.map((data, index) => (
                                            <TableRow key={data.lastUpdated} isEven={index % 2 === 0}>
                                                <TableCell>{data.tahun}</TableCell>
                                                <TableCell>{data.minggu}</TableCell>
                                                <TableCell>{data.subUnit}</TableCell>
                                                <TableCell className='text-right'>{formatRibuan(data.jumlah)}</TableCell>
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
                            <Pagination links={mingguan.links} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
