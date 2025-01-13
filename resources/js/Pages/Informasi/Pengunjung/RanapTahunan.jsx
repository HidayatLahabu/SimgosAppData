import React, { useState } from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import { formatRibuan } from '@/utils/formatRibuan';

export default function RanapTahunan({ ranapTahunan }) {

    const headers = [
        { name: "TAHUN", className: "w-[10%]" },
        { name: "PASIEN MASUK", className: "text-right" },
        { name: "PASIEN DIRAWAT", className: "text-right" },
        { name: "PASIEN KELUAR", className: "text-right" },
    ];

    return (
        <div className="max-w-full mx-auto w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                    <h1 className="uppercase text-center font-extrabold text-xl text-indigo-700 dark:text-gray-200 mb-2">
                        Pengunjung Rawat Inap Tahunan
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
                            {ranapTahunan.data.length > 0 ? (
                                ranapTahunan.data.map((data, index) => (
                                    <TableRow key={data.bulan} isEven={index % 2 === 0}>
                                        <TableCell>{data.tahun}</TableCell>
                                        <TableCell className='text-right'>{formatRibuan(data.masuk)}</TableCell>
                                        <TableCell className='text-right'>{formatRibuan(data.dirawat)}</TableCell>
                                        <TableCell className='text-right'>{formatRibuan(data.keluar)}</TableCell>
                                    </TableRow>
                                ))
                            ) : (
                                <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                    <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                </tr>
                            )}
                        </tbody>
                    </Table>
                    <Pagination links={ranapTahunan.links} />
                </div>
            </div>
        </div>
    );
}
