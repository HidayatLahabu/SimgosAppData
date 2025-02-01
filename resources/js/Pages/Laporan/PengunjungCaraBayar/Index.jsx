import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import Cetak from './Cetak';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';

export default function PengunjungCaraBayar({
    auth,
    dataTable,
    ruangan,
    caraBayar,
    tglAwal,
    tglAkhir,
}) {

    const headers = [
        { name: "ID", className: "w-[5%]" },
        { name: "CARA BAYAR" },
        { name: "LAKI-LAKI", className: "text-center w-[9%]" },
        { name: "PEREMPUAN", className: "text-center w-[9%]" },
        { name: "BARU", className: "text-center w-[7%]" },
        { name: "LAMA", className: "text-center w-[7%]" },
        { name: "JUMLAH", className: "text-center w-[9%]" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <h1 className="uppercase text-center font-bold text-2xl text-gray-100 pt-4">
                            LAPORAN PENGUNJUNG PER CARA BAYAR
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
                                                <TableRow key={data.IDCARABAYAR} isEven={index % 2 === 0}>
                                                    <TableCell>{data.IDCARABAYAR}</TableCell>
                                                    <TableCell>{data.CARABAYAR || 0}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.LAKILAKI) || 0}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.PEREMPUAN) || 0}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.BARU) || 0}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.LAMA) || 0}</TableCell>
                                                    <TableCell className='text-center'>{formatRibuan(data.JUMLAH) || 0}</TableCell>
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

