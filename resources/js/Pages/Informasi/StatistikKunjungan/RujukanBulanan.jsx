import React, { useState } from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import { formatRibuan } from '@/utils/formatRibuan';

export default function RujukanBulanan({ rujukanBulanan }) {

    const headers = [
        { name: "TAHUN", className: "w-[7%]" },
        { name: "BULAN" },
        { name: "MASUK", className: "text-right" },
        { name: "KELUAR", className: "text-right" },
        { name: "BALIK", className: "text-right" },
    ];

    // Array nama bulan
    const bulanNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    return (
        <div className="max-w-full mx-auto w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">

                    <h1 className="uppercase text-center font-bold text-2xl pb-2">
                        Rujukan Bulanan
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
                            {rujukanBulanan.data.length > 0 ? (
                                rujukanBulanan.data.map((data, index) => (
                                    <TableRow key={data.bulan} isEven={index % 2 === 0}>
                                        <TableCell>{data.tahun}</TableCell>
                                        <TableCell>{bulanNames[data.bulan - 1]}</TableCell>
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
                    <Pagination links={rujukanBulanan.links} />
                </div>
            </div>
        </div>
    );
}
