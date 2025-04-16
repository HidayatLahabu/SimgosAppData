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

export default function RanapPerUnit({
    auth,
    items,
    tgl_awal,
    tgl_akhir
}) {

    const headers = [
        { name: "RUANGAN PELAYANAN" },
        { name: "PASIEN AWAL", className: "text-center text-wrap w-[6%]" },
        { name: "PASIEN MASUK", className: "text-center text-wrap w-[6%]" },
        { name: "KELUAR HIDUP", className: "text-center text-wrap w-[6%]" },
        { name: "KELUAR MATI", className: "text-center text-wrap w-[6%]" },
        { name: "LAMA DIRAWAT", className: "text-center text-wrap w-[6%]" },
        { name: "PASIEN AKHIR", className: "text-center text-wrap w-[6%]" },
        { name: "HARI PERAWATAN", className: "text-center text-wrap w-[6%]" },
        { name: "JUMLAH BED", className: "text-center w-[6%]" },
        { name: "BOR", className: "text-center w-[6%]" },
        { name: "AVLOS", className: "text-center text-wrap w-[6%]" },
        { name: "TOI", className: "text-center text-wrap w-[6%]" },
        { name: "BTO", className: "text-center text-wrap w-[6%]" },
        { name: "NDR", className: "text-center text-wrap w-[6%]" },
        { name: "GDR", className: "text-center text-wrap w-[6%]" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">LAPORAN KEGIATAN RAWAT INAP PER UNIT</h1>
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
                                                <TableCell className='text-center'>{formatRibuan(data.AWAL)}</TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(parseFloat(data.MASUK || 0) + parseFloat(data.PINDAHAN || 0))}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(parseFloat(data.DIPINDAHKAN || 0) + parseFloat(data.HIDUP || 0))}
                                                </TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.MATI)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.LD)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.SISA)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.HP)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.JMLTT)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.BOR)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.AVLOS)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.BTO)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.TOI)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.NDR)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.GDR)}</TableCell>
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
                <Cetak
                />
            </div>

        </AuthenticatedLayout>
    );
}
