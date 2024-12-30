import React, { useState, useEffect } from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import { formatDate } from '@/utils/formatDate';

export default function RujukanTable({ tableRujukan }) {
    // State to track the current pagination links for each table
    const [rujukanLinks, setRujukanLinks] = useState(tableRujukan.linksRujukan);

    const handleRujukanPageChange = (newLinks) => {
        setRujukanLinks(newLinks);
    };

    return (
        <div className="max-w-full mx-auto w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                    <h1 className="uppercase text-center font-extrabold text-xl text-indigo-700 dark:text-yellow-500 mb-2">
                        Rujukan Harian
                    </h1>
                    <Table>
                        <TableHeader>
                            <tr>
                                <TableHeaderCell className='text-nowrap w-[20%]'>TANGGAL</TableHeaderCell>
                                <TableHeaderCell className='text-right'>MASUK</TableHeaderCell>
                                <TableHeaderCell className='text-right'>KELUAR</TableHeaderCell>
                                <TableHeaderCell className='text-right'>BALIK</TableHeaderCell>
                                <TableHeaderCell className='w-[13%]'>TANGGAL UPDATED</TableHeaderCell>
                            </tr>
                        </TableHeader>
                        <tbody>
                            {Array.isArray(tableRujukan?.dataRujukan) && tableRujukan.dataRujukan.length > 0 ? (
                                tableRujukan.dataRujukan.map((data, index) => (
                                    <TableRow key={data.tanggal} isEven={index % 2 === 0}>
                                        <TableCell>{formatDate(data.tanggal)}</TableCell>
                                        <TableCell className='text-right'>{data.masuk}</TableCell>
                                        <TableCell className='text-right'>{data.keluar}</TableCell>
                                        <TableCell className='text-right'>{data.balik}</TableCell>
                                        <TableCell>{data.tanggalUpdated}</TableCell>
                                    </TableRow>
                                ))
                            ) : (
                                <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                    <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                </tr>
                            )}
                        </tbody>
                    </Table>
                    <Pagination
                        links={rujukanLinks}
                        onPageChange={handleRujukanPageChange}
                    />
                </div>
            </div>
        </div>
    )
}

