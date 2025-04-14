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

export default function PasienBelumGrouping({
    auth,
    dataTable,
    tglAwal,
    tglAkhir,
    ruangan,
}) {

    const headers = [
        { name: "MASUK", className: "w-[8%]" },
        { name: "PULANG", className: "w-[8%]" },
        { name: "NOPEN", className: "w-[7%]" },
        { name: "NORM", className: "w-[7%]" },
        { name: "NAMA PASIEN", className: "text-center" },
        { name: "SEP", className: "w-[11%]" },
        { name: "DOKTER", className: "text-wrap" },
        { name: "LOS", className: "w-[3%]" },
        { name: "UNIT PELAYANAN", className: "text-wrap" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">

                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <h1 className="uppercase text-center font-bold text-2xl text-gray-100 pt-4">
                            LAPORAN PASIEN BELUM FINAL GROUPING
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

                                        {dataTable.data.map((data, index) => (
                                            <TableRow key={data.NOPEN} isEven={index % 2 === 0}>
                                                <TableCell>{data.TGLMASUK}</TableCell>
                                                <TableCell>{data.TGLKELUAR}</TableCell>
                                                <TableCell>{data.NOMOR}</TableCell>
                                                <TableCell>{data.NORM}</TableCell>
                                                <TableCell>{data.NAMAPASIEN}</TableCell>
                                                <TableCell>{data.NOSEP}</TableCell>
                                                <TableCell>{data.DPJP}</TableCell>
                                                <TableCell>{data.LOS}</TableCell>
                                                <TableCell>{data.UNITPELAYANAN}</TableCell>
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
                                </Table>
                                <Pagination links={dataTable.links || []} />
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
