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

export default function KunjunganPerUnit({
    auth,
    tglAwal,
    tglAkhir,
    dataTable,
    total,
}) {

    const headers = [
        { name: "UNIT LAYANAN" },
        { name: "LAKI-LAKI", className: "text-center w-[7%]" },
        { name: "PEREMPUAN", className: "text-center  w-[7%]" },
        { name: "KASUS BARU", className: "text-wrap text-center w-[7%]" },
        { name: "KASUS LAMA", className: "text-wrap text-center w-[7%]" },
        { name: "UMUM", className: "text-center w-[7%]" },
        { name: "JKN", className: "text-center w-[7%]" },
        { name: "INHEALT", className: "text-center w-[7%]" },
        { name: "JKD", className: "text-center w-[7%]" },
        { name: "IKS", className: "text-center w-[7%]" },
        { name: "JUMLAH", className: "text-center w-[7%]" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">

                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <h1 className="uppercase text-center font-bold text-2xl text-gray-100 pt-4">
                            LAPORAN KUNJUNGAN PER UNIT
                        </h1>
                        <p className="text-center text-gray-100 pb-4">
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
                                        {dataTable.map((item, index) => (
                                            <TableRow key={`${item.SUBUNIT}-${index}`} isEven={index % 2 === 0} className='text-xs'>
                                                <TableCell>{item.SUBUNIT}</TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.LAKILAKI)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.PEREMPUAN)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.BARU)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.LAMA)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.UMUM)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.JKN)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.INHEALT)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.JKD)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.IKS)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.JUMLAH)}
                                                </TableCell>
                                            </TableRow>
                                        ))}
                                        {dataTable.length === 0 && (
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
                                            <TableFooterCell className='text-center'>{formatRibuan(total.LAKILAKI) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.PEREMPUAN) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.BARU) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.LAMA) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.UMUM) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.JKN) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.INHEALT) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.JKD) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.IKS) || 0}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.JUMLAH) || 0}</TableFooterCell>
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
                />
            </div>

        </AuthenticatedLayout >
    );
}
