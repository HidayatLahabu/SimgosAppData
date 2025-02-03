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
import { formatRibuan } from '@/utils/formatRibuan';

export default function LaporanRl32({
    auth,
    tgl_awal,
    tgl_akhir,
    data,
}) {

    const headers = [
        { name: "KODE RS", className: "w-[7%]" },
        { name: "NAMA RUMAH SAKIT" },
        { name: "JENIS PELAYANAN", className: "w-[14%]" },
        { name: "TOTAL PASIEN RUJUKAN", className: "text-wrap text-center w-[14%]" },
        { name: "TOTAL PASIEN NON RUJUKAN", className: "text-wrap text-center w-[13%]" },
        { name: "TINDAK LANJUT PELAYANAN DIRAWAT", className: "text-wrap text-center w-[13%]" },
        { name: "TINDAK LANJUT PELAYANAN DIRUJUK", className: "text-wrap text-center w-[13%]" },
        { name: "TINDAK LANJUT PELAYANAN PULANG", className: "text-wrap text-center w-[13%]" },
        { name: "MENINGGAL DI IGD", className: "text-wrap text-center w-[13%]" },
        { name: "DOA", className: "text-wrap text-center w-[13%]" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">

                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <h1 className="uppercase text-center font-bold text-2xl text-gray-100 pt-4">
                            LAPORAN RL 3.2 - RAWAT DARURAT
                        </h1>
                        <p className="text-center text-gray-100 pb-4">
                            <strong>Periode Tanggal: </strong>{formatDate(tgl_awal)} s.d {formatDate(tgl_akhir)}
                        </p>
                        <div className="pl-5 pr-5 pb-5 text-gray-900 dark:text-gray-100 w-full">
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
                                            <TableRow key={`${item.KODERS}-${index}`} isEven={index % 2 === 0}>
                                                <TableCell>{item.KODERS}</TableCell>
                                                <TableCell>{item.NAMAINST}</TableCell>
                                                <TableCell className='uppercase'>{item.DESKRIPSI}</TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.RUJUKAN)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.NONRUJUKAN)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.DIRAWAT)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.DIRUJUK)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.PULANG)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.MENINGGAL)}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(item.DOA)}
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
                </div>
            </div>

            <div className="w-full">
                <Cetak
                />
            </div>

        </AuthenticatedLayout>
    );
}
