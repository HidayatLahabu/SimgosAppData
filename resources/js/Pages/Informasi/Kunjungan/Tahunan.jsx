import React, { useState } from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function Tahunan({ tahunan }) {

    const headers = [
        { name: "TAHUN", className: "w-[9%]" },
        { name: "JENIS KUNJUNGAN" },
        { name: "SUB UNIT" },
        { name: "KUNJUNGAN", className: "text-right w-[10%]" },
        { name: "LAST UPDATED" },
    ];

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:pl-2 sm:pr-5 lg:pl-2 lg:pr-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">
                                Kunjungan Rawat Jalan Tahunan
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
                                    {tahunan.data.length > 0 ? (
                                        tahunan.data.map((data, index) => (
                                            <TableRow key={data.lastUpdated} isEven={index % 2 === 0}>
                                                <TableCell>{data.tahun}</TableCell>
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
                            <Pagination links={tahunan.links} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
