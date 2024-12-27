import React, { useState } from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import { formatDate } from '@/utils/formatDate';

export default function Index({ auth, tablePengunjung, tableRanap }) {

    // State to track the current pagination links for each table
    const [pengunjungLinks, setPengunjungLinks] = useState(tablePengunjung.linksPengunjung);
    const [ranapLinks, setRanapLinks] = useState(tableRanap.linksRanap);

    const handlePengunjungPageChange = (newLinks) => {
        setPengunjungLinks(newLinks);
    };

    const handleRanapPageChange = (newLinks) => {
        setRanapLinks(newLinks);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Informasi" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">
                                    Data Pengunjung
                                </h1>
                                <div className="flex space-x-4">
                                    {/* Kunjungan Table */}
                                    <div className="w-1/2">
                                        <h1 className="uppercase text-center font-bold text-2xl pb-2">
                                            Rawat Jalan & Rawat Darurat
                                        </h1>
                                        <Table>
                                            <TableHeader>
                                                <tr>
                                                    <TableHeaderCell className='text-nowrap'>TANGGAL</TableHeaderCell>
                                                    <TableHeaderCell className='text-right'>RAWAT JALAN</TableHeaderCell>
                                                    <TableHeaderCell className='text-right'>RAWAT DARURAT</TableHeaderCell>
                                                    <TableHeaderCell className='text-right'>JUMLAH</TableHeaderCell>
                                                    <TableHeaderCell className='w-[13%]'>LAST UPDATED</TableHeaderCell>
                                                </tr>
                                            </TableHeader>
                                            <tbody>
                                                {Array.isArray(tablePengunjung?.dataPengunjung) && tablePengunjung.dataPengunjung.length > 0 ? (
                                                    tablePengunjung.dataPengunjung.map((data, index) => (
                                                        <TableRow key={data.tanggalUpdated} isEven={index % 2 === 0}>
                                                            <TableCell>{formatDate(data.tanggal)}</TableCell>
                                                            <TableCell className='text-right'>{data.rajal}</TableCell>
                                                            <TableCell className='text-right'>{data.darurat}</TableCell>
                                                            <TableCell className='text-right'>{data.semua}</TableCell>
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
                                            links={pengunjungLinks}
                                            onPageChange={handlePengunjungPageChange}
                                        />
                                    </div>

                                    {/* Rujukan Table */}
                                    <div className="w-1/2">
                                        <h1 className="uppercase text-center font-bold text-2xl pb-2">
                                            Rawat Inap
                                        </h1>
                                        <Table>
                                            <TableHeader>
                                                <tr>
                                                    <TableHeaderCell className='text-nowrap'>TANGGAL</TableHeaderCell>
                                                    <TableHeaderCell className='text-right'>PASIEN MASUK</TableHeaderCell>
                                                    <TableHeaderCell className='text-right'>PASIEN DIRAWAT</TableHeaderCell>
                                                    <TableHeaderCell className='text-right'>PASIEN KELUAR</TableHeaderCell>
                                                    <TableHeaderCell className='w-[13%]'>TANGGAL UPDATED</TableHeaderCell>
                                                </tr>
                                            </TableHeader>
                                            <tbody>
                                                {Array.isArray(tableRanap?.dataRanap) && tableRanap.dataRanap.length > 0 ? (
                                                    tableRanap.dataRanap.map((data, index) => (
                                                        <TableRow key={data.tanggalUpdated} isEven={index % 2 === 0}>
                                                            <TableCell>{formatDate(data.tanggal)}</TableCell>
                                                            <TableCell className='text-right'>{data.masuk}</TableCell>
                                                            <TableCell className='text-right'>{data.dirawat}</TableCell>
                                                            <TableCell className='text-right'>{data.keluar}</TableCell>
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
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
