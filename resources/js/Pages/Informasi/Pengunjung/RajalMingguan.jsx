import React, { useState } from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import { formatRibuan } from '@/utils/formatRibuan';

export default function RajalMingguan({ rajalMingguan }) {

    const [rajalMingguanLinks, setRajalMingguanLinks] = useState(rajalMingguan.links);

    const handleRajalMingguanChange = (newLinks) => {
        setRajalMingguanLinks(newLinks);
    };

    return (
        <div className="max-w-full mx-auto w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                    <h1 className="uppercase text-center font-extrabold text-xl text-indigo-700 dark:text-gray-200 mb-2">
                        Pengunjung Rawat Jalan & Darurat Mingguan
                    </h1>
                    <Table>
                        <TableHeader>
                            <tr>
                                <TableHeaderCell className='text-nowrap w-[5%]'>TAHUN</TableHeaderCell>
                                <TableHeaderCell className='text-nowrap w-[12%]'>MINGGU KE</TableHeaderCell>
                                <TableHeaderCell className='text-right'>RAWAT JALAN</TableHeaderCell>
                                <TableHeaderCell className='text-right'>RAWAT DARURAT</TableHeaderCell>
                                <TableHeaderCell className='text-right'>JUMLAH</TableHeaderCell>
                            </tr>
                        </TableHeader>
                        <tbody>
                            {Array.isArray(rajalMingguan?.data) && rajalMingguan.data.length > 0 ? (
                                rajalMingguan.data.map((data, index) => (
                                    <TableRow key={data.minggu} isEven={index % 2 === 0}>
                                        <TableCell>{data.tahun}</TableCell>
                                        <TableCell>{data.minggu}</TableCell>
                                        <TableCell className='text-right'>{formatRibuan(data.rajal)}</TableCell>
                                        <TableCell className='text-right'>{formatRibuan(data.darurat)}</TableCell>
                                        <TableCell className='text-right'>{formatRibuan(data.semua)}</TableCell>
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
                        links={rajalMingguanLinks}
                        onPageChange={handleRajalMingguanChange}
                    />
                </div>
            </div>
        </div>
    );
}
