import React, { useState } from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';

export default function RanapHarian({ ranapHarian }) {

    const [ranapLinks, setRanapLinks] = useState(ranapHarian.links);

    const handleRanapPageChange = (newLinks) => {
        setRanapLinks(newLinks);
    };

    return (
        <div className="max-w-full mx-auto w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                    <h1 className="uppercase text-center font-extrabold text-xl text-indigo-700 dark:text-gray-200 mb-2">
                        Pengunjung Rawat Inap Harian
                    </h1>
                    <Table>
                        <TableHeader>
                            <tr>
                                <TableHeaderCell className='text-nowrap'>TANGGAL</TableHeaderCell>
                                <TableHeaderCell className='text-right'>PASIEN MASUK</TableHeaderCell>
                                <TableHeaderCell className='text-right'>PASIEN DIRAWAT</TableHeaderCell>
                                <TableHeaderCell className='text-right'>PASIEN KELUAR</TableHeaderCell>
                                <TableHeaderCell className='w-[13%]'>LAST UPDATED</TableHeaderCell>
                            </tr>
                        </TableHeader>
                        <tbody>
                            {Array.isArray(ranapHarian?.data) && ranapHarian.data.length > 0 ? (
                                ranapHarian.data.map((data, index) => (
                                    <TableRow key={data.tanggalUpdated} isEven={index % 2 === 0}>
                                        <TableCell>{formatDate(data.tanggal)}</TableCell>
                                        <TableCell className='text-right'>{formatRibuan(data.masuk)}</TableCell>
                                        <TableCell className='text-right'>{formatRibuan(data.dirawat)}</TableCell>
                                        <TableCell className='text-right'>{formatRibuan(data.keluar)}</TableCell>
                                        <TableCell className='text-nowrap'>{data.lastUpdated}</TableCell>
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
                        links={ranapLinks}
                        onPageChange={handleRanapPageChange}
                    />

                </div>
            </div>
        </div>
    )
}

