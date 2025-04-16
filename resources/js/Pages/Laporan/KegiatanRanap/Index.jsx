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

export default function KegiatanRanap({
    auth,
    dataTable,
    ruangan,
    caraBayar,
    tglAwal,
    tglAkhir,
    total,
}) {

    const headers = [
        { name: "UNIT LAYANAN" },
        { name: "PASIEN AWAL", className: "text-center text-wrap w-[7%]" },
        { name: "PASIEN MASUK", className: "text-center text-wrap w-[7%]" },
        { name: "PINDAHAN", className: "text-center w-[7%]" },
        { name: "DIPINDAHKAN", className: "text-center w-[7%]" },
        { name: "KELUAR HIDUP", className: "text-wrap text-center w-[7%]" },
        { name: "MENINGGAL", className: "text-center w-[7%]" },
        { name: "PASIEN AKHIR", className: "text-center text-wrap w-[7%]" },
        { name: "LAMA DIRAWAT", className: "text-wrap text-center w-[7%]" },
        { name: "HARI PERAWATAN", className: "text-wrap text-center w-[7%]" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <h1 className="uppercase text-center font-bold text-2xl text-gray-100 pt-4">
                            LAPORAN KEGIATAN RAWAT INAP
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
                                                <TableRow key={data.ID} isEven={index % 2 === 0}>
                                                    <TableCell>{data.DESKRIPSI}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.AWAL)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.MASUK)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.PINDAHAN)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.DIPINDAHKAN)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.HIDUP)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.MATI)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.SISA)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.LD)}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.HP)}</TableCell>
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
                                            <TableFooterCell className='text-center'>{formatRibuan(total.AWAL)}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.MASUK)}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.PINDAHAN)}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.DIPINDAHKAN)}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.HIDUP)}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.MATI)}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.SISA)}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.LD)}</TableFooterCell>
                                            <TableFooterCell className='text-center'>{formatRibuan(total.HP)}</TableFooterCell>
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

