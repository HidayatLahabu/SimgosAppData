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

export default function LaporanRl31({
    auth,
    items,
    tgl_awal,
    tgl_akhir
}) {

    const headers = [
        { name: "JENIS PELAYANAN" },
        { name: "PASIEN AWAL", className: "text-center text-wrap w-[6%]" },
        { name: "PASIEN MASUK", className: "text-center text-wrap w-[6%]" },
        { name: "PASIEN KELUAR", className: "text-center text-wrap w-[6%]" },
        { name: "MATI < 48 JAM", className: "text-center text-wrap w-[6%]" },
        { name: "MATI >= 48 JAM", className: "text-center text-wrap w-[6%]" },
        { name: "JUMLAH LAMA", className: "text-center text-wrap w-[6%]" },
        { name: "PASIEN AKHIR", className: "text-center text-wrap w-[6%]" },
        { name: "JUMLAH HARI", className: "text-center text-wrap w-[6%]" },
        { name: "VVIP", className: "text-center w-[6%]" },
        { name: "VIP", className: "text-center w-[6%]" },
        { name: "KELAS I", className: "text-center text-wrap w-[6%]" },
        { name: "KELAS II", className: "text-center text-wrap w-[6%]" },
        { name: "KELAS III", className: "text-center text-wrap w-[6%]" },
        { name: "KELAS KHUSUS", className: "text-center text-wrap w-[6%]" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">LAPORAN RL 3.1 - RAWAT INAP</h1>
                            <p className="text-center pb-4">
                                <strong>Periode Tanggal: </strong>{formatDate(tgl_awal)} s.d {formatDate(tgl_akhir)}
                            </p>
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
                                        {items.map((data, index) => (
                                            <TableRow key={data.KODE}>
                                                <TableCell>{data.DESKRIPSI}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.AWAL)}</TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(parseFloat(data.MASUK || 0) + parseFloat(data.PINDAHAN || 0))}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {formatRibuan(parseFloat(data.DIPINDAHKAN || 0) + parseFloat(data.HIDUP || 0))}
                                                </TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.MATIKURANG48)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.MATILEBIH48)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.LD)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.SISA)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.HP)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.VVIP)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.VIP)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.KLSI)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.KLSII)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.KLSIII)}</TableCell>
                                                <TableCell className='text-center'>{formatRibuan(data.KLSKHUSUS)}</TableCell>
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
