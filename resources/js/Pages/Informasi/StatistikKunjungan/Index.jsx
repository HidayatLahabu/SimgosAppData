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

export default function Index({ auth, tableKunjungan, tableRujukan }) {
    // State to track the current pagination links for each table
    const [kunjunganLinks, setKunjunganLinks] = useState(tableKunjungan.linksKunjungan);
    const [rujukanLinks, setRujukanLinks] = useState(tableRujukan.linksRujukan);

    const handleKunjunganPageChange = (newLinks) => {
        setKunjunganLinks(newLinks);
    };

    const handleRujukanPageChange = (newLinks) => {
        setRujukanLinks(newLinks);
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Informasi" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">
                                    Data Statistik
                                </h1>
                                <div className="flex space-x-4">
                                    {/* Kunjungan Table */}
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
                                                {Array.isArray(tableKunjungan?.dataKunjungan) && tableKunjungan.dataKunjungan.length > 0 ? (
                                                    tableKunjungan.dataKunjungan.map((data, index) => (
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
                                            links={kunjunganLinks}
                                            onPageChange={handleKunjunganPageChange}
                                        />
                                    </div>

                                    {/* Rujukan Table */}
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
                                                {Array.isArray(tableRujukan?.dataRujukan) && tableRujukan.dataRujukan.length > 0 ? (
                                                    tableRujukan.dataRujukan.map((data, index) => (
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
                                            links={rujukanLinks}
                                            onPageChange={handleRujukanPageChange}
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
