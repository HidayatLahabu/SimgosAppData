import React from 'react';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import Pagination from "@/Components/Pagination";
import { formatDate } from '@/utils/formatDate';

export default function Rujukan({ dataRujukan, linksRujukan, onPageChange }) {
    return (
        <div className="w-1/2">
            <h1 className="uppercase text-center font-bold text-2xl pb-2">
                Rujukan
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
                    {Array.isArray(dataRujukan) && dataRujukan.length > 0 ? (
                        dataRujukan.map((data, index) => (
                            <TableRow key={data.tanggalUpdated} isEven={index % 2 === 0}>
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
                links={linksRujukan}
                onPageChange={onPageChange}
            />
        </div>
    );
}
