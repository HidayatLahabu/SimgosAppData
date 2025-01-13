import React, { useState } from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import { formatRibuan } from '@/utils/formatRibuan';

export default function RanapMingguan({ ranapMingguan }) {

    const [ranapMingguanLinks, setRanapMingguanLinks] = useState(ranapMingguan.links);

    const handleRanapMingguanChange = (newLinks) => {
        setRanapMingguanLinks(newLinks);
    };

    return (
        <div className="max-w-full mx-auto w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                    <h1 className="uppercase text-center font-extrabold text-xl text-indigo-700 dark:text-gray-200 mb-2">
                        Pengunjung Rawat Inap Mingguan
                    </h1>
                    <Table>
                        <TableHeader>
                            <tr>
                                <TableHeaderCell className='text-nowrap w-[5%]'>TAHUN</TableHeaderCell>
                                <TableHeaderCell className='text-nowrap w-[12%]'>MINGGU KE</TableHeaderCell>
                                <TableHeaderCell className='text-right'>PASIEN MASUK</TableHeaderCell>
                                <TableHeaderCell className='text-right'>PASIEN DIRAWAT</TableHeaderCell>
                                <TableHeaderCell className='text-right'>PASIEN KELUAR</TableHeaderCell>
                            </tr>
                        </TableHeader>
                        <tbody>
                            {Array.isArray(ranapMingguan?.data) && ranapMingguan.data.length > 0 ? (
                                ranapMingguan.data.map((data, index) => (
                                    <TableRow key={data.minggu} isEven={index % 2 === 0}>
                                        <TableCell>{data.tahun}</TableCell>
                                        <TableCell>{data.minggu}</TableCell>
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
                    <Pagination
                        links={ranapMingguanLinks}
                        onPageChange={handleRanapMingguanChange}
                    />
                </div>
            </div>
        </div>
    );
}
