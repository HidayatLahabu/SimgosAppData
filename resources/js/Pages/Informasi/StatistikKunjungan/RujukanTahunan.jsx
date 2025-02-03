import React, { useState } from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import { formatRibuan } from '@/utils/formatRibuan';

export default function RujukanTahunan({ rujukanTahunan }) {

    const headers = [
        { name: "TAHUN", className: "w-[7%]" },
        { name: "MASUK", className: "text-right" },
        { name: "KELUAR", className: "text-right" },
        { name: "BALIK", className: "text-right" },
    ];

    return (
        <div className="max-w-full mx-auto w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">

                    <h1 className="uppercase text-center font-bold text-2xl pb-2">
                        Rujukan Tahunan
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
                            {rujukanTahunan.data.length > 0 ? (
                                rujukanTahunan.data.map((data, index) => (
                                    <TableRow key={data.tahun} isEven={index % 2 === 0}>
                                        <TableCell>{data.tahun}</TableCell>
                                        <TableCell className='text-right'>{formatRibuan(data.masuk)}</TableCell>
                                        <TableCell className='text-right'>{formatRibuan(data.keluar)}</TableCell>
                                        <TableCell className='text-right'>{formatRibuan(data.balik)}</TableCell>
                                    </TableRow>
                                ))
                            ) : (
                                <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                    <td colSpan="5" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                </tr>
                            )}
                        </tbody>
                    </Table>
                    <Pagination links={rujukanTahunan.links} />
                </div>
            </div>
        </div>
    );
}
