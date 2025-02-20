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

export default function PengunjungRekap({
    auth,
    dataTable,
    ruangan,
    tglAwal,
    tglAkhir,
    total,
}) {

    const headers = [
        { name: "DESKRIPSI" },
        { name: "LAKI-LAKI", className: "text-center w-[9%]" },
        { name: "PEREMPUAN", className: "text-center w-[9%]" },
        { name: "UMUM", className: "text-center w-[7%]" },
        { name: "JKN", className: "text-center w-[7%]" },
        { name: "INHEALT", className: "text-center w-[7%]" },
        { name: "JKD", className: "text-center w-[7%]" },
        { name: "IKS", className: "text-center w-[7%]" },
        { name: "JUMLAH", className: "text-center w-[9%]" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <h1 className="uppercase text-center font-bold text-2xl text-gray-100 pt-4">
                            LAPORAN REKAPITULASI KUNJUNGAN
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
                                                <TableRow key={data.IDSTATUSPENGUNJUNG} isEven={index % 2 === 0}>
                                                    <TableCell>
                                                        {data.IDSTATUSPENGUNJUNG === 1 ? "Pengunjung Baru" : "Pengunjung Lama"}
                                                    </TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.LAKILAKI) || 0}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.PEREMPUAN) || 0}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.UMUM) || 0}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.JKN) || 0}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.INHEALT) || 0}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.JKD) || 0}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.IKS) || 0}</TableCell>
                                                    <TableCell className='text-center text-yellow-500'>{formatRibuan(data.JUMLAH) || 0}</TableCell>
                                                </TableRow>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="7" className="px-3 py-3 text-center">
                                                    Tidak ada data yang dapat ditampilkan
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                    <TableFooter>
                                        <TableRow>
                                            <TableFooterCell className='text-right'>TOTAL</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.LAKILAKI) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.PEREMPUAN) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.UMUM) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.JKN) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.INHEALTH) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.JKD) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.IKS) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center text-yellow-500'>{formatRibuan(total.JUMLAH) || 0}</TableFooterCell>
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
                />
            </div>

        </AuthenticatedLayout >
    );
}

