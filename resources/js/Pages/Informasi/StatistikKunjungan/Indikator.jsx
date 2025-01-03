import React, { useState } from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import { formatDate } from '@/utils/formatDate';

export default function IndikatorTable({ statistikIndikator }) {

    // State to track the current pagination links for each table
    const [indikatorLinks, setIndikatorLinks] = useState(statistikIndikator.linksIndikator);

    const handleIndikatorPageChange = (newLinks) => {
        setIndikatorLinks(newLinks);
    };

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-extrabold text-xl text-indigo-700 dark:text-gray-200 mb-2">
                                Statistik Indikator Pelayanan
                            </h1>

                            <Table>
                                <TableHeader>
                                    <tr>
                                        <TableHeaderCell className='w-[10%]'>TAHUN</TableHeaderCell>
                                        <TableHeaderCell>PERIODE</TableHeaderCell>
                                        <TableHeaderCell className='text-center w-[10%]'>BOR</TableHeaderCell>
                                        <TableHeaderCell className='text-center w-[10%]'>AVLOS</TableHeaderCell>
                                        <TableHeaderCell className='text-center w-[10%]'>BTO</TableHeaderCell>
                                        <TableHeaderCell className='text-center w-[10%]'>TOI</TableHeaderCell>
                                        <TableHeaderCell className='text-center w-[10%]'>NDR</TableHeaderCell>
                                        <TableHeaderCell className='text-center w-[10%]'>GDR</TableHeaderCell>
                                        <TableHeaderCell>TANGGAL UPDATED</TableHeaderCell>
                                    </tr>
                                </TableHeader>
                                <tbody>
                                    {Array.isArray(statistikIndikator?.dataIndikator) && statistikIndikator.dataIndikator.length > 0 ? (
                                        statistikIndikator.dataIndikator.map((data, index) => (
                                            <TableRow key={data.TANGGAL_UPDATED} isEven={index % 2 === 0}>
                                                <TableCell>{data.TAHUN}</TableCell>
                                                <TableCell>{data.PERIODE == 'tw1' ? 'Triwulan I' : data.PERIODE == 'tw2' ? 'Triwulan II' : data.PERIODE == 'tw3' ? 'Triwulan III' : 'Triwulan IV'}</TableCell>
                                                <TableCell className='text-center'>{data.BOR}</TableCell>
                                                <TableCell className='text-center'>{data.ALOS}</TableCell>
                                                <TableCell className='text-center'>{data.BTO}</TableCell>
                                                <TableCell className='text-center'>{data.TOI}</TableCell>
                                                <TableCell className='text-center'>{data.NDR}</TableCell>
                                                <TableCell className='text-center'>{data.GDR}</TableCell>
                                                <TableCell>{data.TANGGAL_UPDATED}</TableCell>
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
                                links={indikatorLinks}
                                onPageChange={handleIndikatorPageChange}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

