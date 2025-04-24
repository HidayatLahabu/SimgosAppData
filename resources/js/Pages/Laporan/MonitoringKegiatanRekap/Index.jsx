import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import Cetak from './Cetak';

export default function MonitoringKegiatanRekap({
    auth,
    items,
    tgl_awal,
    tgl_akhir
}) {

    const headers = [
        { name: "RUANGAN PELAYANAN" },
        { name: "BELUM TERIMA PENGUNJUNG", className: "text-center text-wrap w-[9%]" },
        { name: "BELUM FINAL KUNJUNGAN", className: "text-center text-wrap w-[9%]" },
        { name: "BELUM TERIMA RESEP", className: "text-center text-wrap w-[9%]" },
        { name: "BELUM TERIMA LABORATORIUM", className: "text-center text-wrap w-[9%]" },
        { name: "BELUM TERIMA RADIOLOGI", className: "text-center text-wrap w-[9%]" },
        { name: "BELUM TERIMA KONSUL", className: "text-center text-wrap w-[9%]" },
        { name: "BELUM TERIMA MUTASI", className: "text-center text-wrap w-[9%]" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">LAPORAN REKAP MONITORING STATUS KEGIATAN</h1>
                            <p className="text-center pb-4">
                                <strong>Periode Tanggal: </strong>{formatDate(tgl_awal)} s.d {formatDate(tgl_akhir)}
                            </p>
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
                                        {items.map((data, index) => (
                                            <TableRow key={data.ID}>
                                                <TableCell>{data.DESKRIPSI}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.BLM_TERIMA_KUNJUNGAN)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.BLM_FINAL_KUNJUNGAN)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.BLM_TERIMA_RESEP)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.BLM_TERIMA_LAB)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.BLM_TERIMA_RAD)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.BLM_TERIMA_KONSUL)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.BLM_TERIMA_MUTASI)}</TableCell>
                                            </TableRow>
                                        ))}
                                        {items.length === 0 && (
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
                <Cetak />
            </div>

        </AuthenticatedLayout>
    );
}
