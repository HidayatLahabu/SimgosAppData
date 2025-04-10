import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import Pagination from "@/Components/Pagination";
import Cetak from './Cetak';
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import { formatDate } from '@/utils/formatDate';

export default function LaporanRP1({
    auth,
    dataTable,
    ruangan,
    tglAwal,
    tglAkhir,
}) {

    const headers = [
        { name: "TANGGAL", className: " text-[10px]" },
        { name: "AWAL", className: "text-center text-[10px] w-[5%]" },
        { name: "MASUK", className: "text-center text-[10px] w-[5%]" },
        { name: "PINDAHAN", className: "text-center text-[10px] w-[5%]" },
        { name: "DIPINDAHKAN", className: "text-center text-[10px] w-[5%]" },
        { name: "KELUAR HIDUP", className: "text-wrap text-center text-[10px] w-[5%]" },
        { name: "MENINGGAL < 48", className: "text-wrap text-center text-[10px] w-[5%]" },
        { name: "MENINGGAL > 48", className: "text-wrap text-center text-[10px] w-[5%]" },
        { name: "PASIEN AKHIR", className: "text-wrap text-center text-[10px] w-[5%]" },
        { name: "LAMA DIRAWAT", className: "text-wrap text-center text-[10px] w-[5%]" },
        { name: "HARI PERAWATAN", className: "text-wrap text-center text-[10px] w-[5%]" },
        { name: "RAWAT PAVILIUN", className: "text-wrap text-center text-[10px] w-[5%]" },
        { name: "RAWAT VVIP", className: "text-wrap text-center text-[10px] w-[5%]" },
        { name: "RAWAT VIP", className: "text-wrap text-center text-[10px] w-[5%]" },
        { name: "RAWAT KELAS I", className: "text-wrap text-center text-[10px] w-[5%]" },
        { name: "RAWAT KELAS II", className: "text-wrap text-center text-[10px] w-[5%]" },
        { name: "RAWAT KELAS III", className: "text-wrap text-center text-[10px] w-[5%]" },
        { name: "RAWAT KHUSUS", className: "text-wrap text-center text-[10px] w-[5%]" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">

                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <h1 className="uppercase text-center font-bold text-2xl text-gray-100 pt-4">
                            LAPORAN RP1 - REKAP SENSUS HARIAN
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
                                        {dataTable.data.length > 0 ? (
                                            dataTable.data.map((data, index) => (
                                                <TableRow key={data.TANGGAL} isEven={index % 2 === 0}>
                                                    <TableCell className='text-[9px]'>{formatDate(data.TANGGAL)}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.AWAL || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.MASUK || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.PINDAHAN || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.DIPINDAHKAN || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.HIDUP || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.MATIKURANG48 || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.MATILEBIH48 || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.SISA || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.LD || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.HP || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.PAV || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.VVIP || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.VIP || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.KLSI || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.KLSII || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.KLSIII || 0}</TableCell>
                                                    <TableCell className='text-center text-[9px]'>{data.KLSKHUSUS || 0}</TableCell>
                                                </TableRow>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="11" className="px-3 py-3 text-center">
                                                    Tidak ada data yang dapat ditampilkan
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </Table>
                                <Pagination links={dataTable.links} />
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
