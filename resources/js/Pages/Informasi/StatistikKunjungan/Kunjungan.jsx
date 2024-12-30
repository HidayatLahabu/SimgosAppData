import React from 'react';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import Pagination from "@/Components/Pagination";
import { formatDate } from '@/utils/formatDate';

export default function Kunjungan({ dataKunjungan, linksKunjungan, onPageChange }) {
    return (
        <div className="w-1/2">
            <h1 className="uppercase text-center font-bold text-2xl pb-2">
                Kunjungan
            </h1>
            <Table>
                <TableHeader>
                    <tr>
                        <TableHeaderCell className='text-nowrap w-[20%]'>TANGGAL</TableHeaderCell>
                        <TableHeaderCell className='text-right'>RAWAT JALAN</TableHeaderCell>
                        <TableHeaderCell className='text-right'>RAWAT DARURAT</TableHeaderCell>
                        <TableHeaderCell className='text-right'>RAWAT INAP</TableHeaderCell>
                        <TableHeaderCell className='w-[13%]'>TANGGAL UPDATED</TableHeaderCell>
                    </tr>
                </TableHeader>
                <tbody>
                    {Array.isArray(dataKunjungan) && dataKunjungan.length > 0 ? (
                        dataKunjungan.map((data, index) => (
                            <TableRow key={data.tanggalUpdated} isEven={index % 2 === 0}>
                                <TableCell>{formatDate(data.tanggal)}</TableCell>
                                <TableCell className='text-right'>{data.rajal}</TableCell>
                                <TableCell className='text-right'>{data.darurat}</TableCell>
                                <TableCell className='text-right'>{data.ranap}</TableCell>
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
                links={linksKunjungan}
                onPageChange={onPageChange}
            />
        </div>
    );
}
