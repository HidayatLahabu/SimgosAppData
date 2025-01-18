import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { formatDate } from '@/utils/formatDate';
import Cetak from './Cetak';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import { formatRibuan } from '@/utils/formatRibuan';

export default function LaporanRl51({
    auth,
    tgl_awal,
    tgl_akhir,
    data,
}) {

    const headers = [
        { name: "JENIS SPESIALISASI", className: "w-[16%]" },
        { name: "RUJUKAN DITERIMA DARI PUSKESMAS", className: "text-wrap text-center" },
        { name: "RUJUKAN DITERIMA DARI FASILITAS KESEHATAN", className: "text-wrap text-center" },
        { name: "RUJUKAN DITERIMA DARI RS LAIN", className: "text-wrap text-center" },
        { name: "RUJUKAN DIKEMBALIKAN KE PUSKESMAS", className: "text-wrap text-center" },
        { name: "RUJUKAN DIKEMBALIKAN KE FASILITAS KESEHATAN", className: "text-wrap text-center" },
        { name: "RUJUKAN DIKEMBALIKAN KE RS ASAL", className: "text-wrap text-center" },
        { name: "DIRUJUK PASIEN RUJUKAN", className: "text-wrap text-center" },
        { name: "DIRUJUK PASIEN DATANG SENDIRI", className: "text-wrap text-center" },
        { name: "DIRUJUK DITERIMA KEMBALI", className: "text-wrap text-center" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">

                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <h1 className="uppercase text-center font-bold text-2xl text-gray-100 pt-4">
                            LAPORAN RL 3.14 - RUJUKAN
                        </h1>
                        <p className="text-center text-gray-100 pb-4">
                            <strong>Periode Tanggal: </strong>{formatDate(tgl_awal)} s.d {formatDate(tgl_akhir)}
                        </p>
                        <div className="pl-5 pr-5 pb-5 text-gray-900 dark:text-gray-100 w-full">
                            <div className="overflow-x-auto">

                                <Table>
                                    <TableHeader>
                                        <tr className='text-xs'>
                                            {headers.map((header, index) => (
                                                <TableHeaderCell key={index} className={header.className || ""}>
                                                    {header.name}
                                                </TableHeaderCell>
                                            ))}
                                        </tr>
                                    </TableHeader>
                                    <tbody>
                                        {data.map((item, index) => (
                                            <TableRow key={`${item.KODERS}-${index}`} isEven={index % 2 === 0} className='text-xs'>
                                                <TableCell>{item.DESKRIPSI}</TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.PUSKESMAS)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.FASKES)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.RS)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.KEMBALIPUSKESMAS)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.KEMBALIFASKES)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.KEMBALIRS)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.PASIENRUJUKAN)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.DATANGSENDIRI)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.DITERIMAKEMBALI)}
                                                </TableCell>
                                            </TableRow>
                                        ))}
                                        {data.length === 0 && (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan={headers.length} className="px-3 py-3 text-center">
                                                    Tidak ada data yang dapat ditampilkan
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </Table>
                            </div>
                        </div>
                    </div>
                </div >
            </div >

            <div className="w-full">
                <Cetak
                />
            </div>

        </AuthenticatedLayout >
    );
}
