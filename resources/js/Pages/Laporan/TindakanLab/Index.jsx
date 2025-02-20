import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { formatDate } from '@/utils/formatDate';
import Cetak from './Cetak';
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import TableFooter from "@/Components/Table/TableFooter";
import TableFooterCell from "@/Components/Table/TableFooterCell";
import { formatRibuan } from '@/utils/formatRibuan';
import { formatNumber } from '@/utils/formatNumber';

export default function TindakanLab({
    auth,
    tgl_awal,
    tgl_akhir,
    data,
    total,
}) {

    const headers = [
        { name: "TINDAKAN" },
        { name: "UMUM", className: "text-center w-[9%]" },
        { name: "BPJS", className: "text-center w-[9%]" },
        { name: "IKS", className: "text-center w-[9%]" },
        { name: "FREKUENSI", className: "text-center w-[9%]" },
        { name: "TAGIHAN", className: "text-right w-[12%]" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <h1 className="uppercase text-center font-bold text-2xl text-gray-100 pt-4">
                            LAPORAN GROUP TINDAKAN LABORATORIUM
                        </h1>
                        <p className="text-center text-gray-100">
                            <strong>Periode Tanggal: </strong>{formatDate(tgl_awal)} s.d {formatDate(tgl_akhir)}
                        </p>
                        <div className="pl-5 pr-5 pb-5 pt-3 text-gray-900 dark:text-gray-100 w-full">
                            <div className="overflow-x-auto">

                                <Table>
                                    <TableHeader>
                                        <tr>
                                            {headers.map((header, index) => (
                                                <TableHeaderCell key={index} className={header.className || ""}>
                                                    {header.name}
                                                </TableHeaderCell>
                                            ))}
                                        </tr>
                                    </TableHeader>
                                    <tbody>
                                        {data.map((item, index) => (
                                            <TableRow key={`${item.LAYANAN}-${index}`} isEven={index % 2 === 0}>
                                                <TableCell>{item.LAYANAN}</TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.UMUM)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.BPJS)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.IKS)}
                                                </TableCell>
                                                <TableCell className="text-center text-yellow-500">
                                                    {formatRibuan(item.FREKUENSI)}
                                                </TableCell>
                                                <TableCell className="text-right">
                                                    {formatNumber(item.TOTALTAGIHAN)}
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
                                    <TableFooter>
                                        <TableRow>
                                            <TableFooterCell className='text-right'>TOTAL</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.UMUM) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.BPJS) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.IKS) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center text-yellow-500'>{formatRibuan(total.FREKUENSI) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-right'>{formatNumber(total.TOTALTAGIHAN) || 0}</TableFooterCell>
                                        </TableRow>
                                    </TableFooter>
                                </Table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="w-full">
                <Cetak
                />
            </div>

        </AuthenticatedLayout>
    );
}
