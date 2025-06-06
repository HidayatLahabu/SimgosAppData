import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import Cetak from './Cetak';
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import TableFooter from "@/Components/Table/TableFooter";
import TableFooterCell from "@/Components/Table/TableFooterCell";
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';

export default function TindakanRekap({
    auth,
    dataTable,
    ruangan,
    caraBayar,
    total,
    tglAwal,
    tglAkhir,
}) {

    const headers = [
        { name: "UNIT PELAYANAN" },
        { name: "NAMA TINDAKAN" },
        { name: "RAWAT JALAN", className: "text-center w-[7%]" },
        { name: "RAWAT DARURAT", className: "text-center w-[7%]" },
        { name: "RAWAT INAP", className: "text-center w-[7%]" },
        { name: "JUMLAH", className: "text-center w-[9%]" },
        { name: "UMUM", className: "text-center w-[7%]" },
        { name: "BPJS", className: "text-center w-[7%]" },
        { name: "IKS", className: "text-center w-[7%]" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <h1 className="uppercase text-center font-bold text-2xl text-gray-100 pt-4">
                            LAPORAN REKAPITULASI TINDAKAN
                        </h1>
                        <p className="text-center text-white pb-4">
                            <strong>Periode Tanggal: </strong>{formatDate(tglAwal)} s.d {formatDate(tglAkhir)}
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
                                        {Array.isArray(dataTable) && dataTable.length > 0 ? (
                                            dataTable.map((data, index) => (
                                                <TableRow key={data.NAMATINDAKAN} isEven={index % 2 === 0}>
                                                    <TableCell>{data.UNITPELAYANAN}</TableCell>
                                                    <TableCell>{data.NAMATINDAKAN}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.RJ)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.RD)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.RI)}</TableCell>
                                                    <TableCell className='text-center text-yellow-500'>{formatRibuan(data.JUMLAH)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.UMUM)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.BPJS)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.IKS)}</TableCell>
                                                </TableRow>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="9" className="px-3 py-3 text-center">
                                                    Tidak ada data yang dapat ditampilkan
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                    <TableFooter>
                                        <TableRow>
                                            <TableFooterCell className='text-right'></TableFooterCell>
                                            <TableFooterCell className='text-right'>TOTAL</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.RJ) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.RD) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.RI) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center text-yellow-500'>{formatRibuan(total.JUMLAH) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.UMUM) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.BPJS) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.IKS) || 0}</TableFooterCell>
                                        </TableRow>
                                    </TableFooter>
                                </Table>
                            </div>
                        </div>
                    </div>
                </div >
            </div >

            <div className="w-full">
                <Cetak
                    ruangan={ruangan}
                    caraBayar={caraBayar}
                />
            </div>

        </AuthenticatedLayout >
    );
}

