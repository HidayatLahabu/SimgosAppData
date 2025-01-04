import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function LaporanRl51({ auth, items, tgl_awal, tgl_akhir }) {

    // Check if items is defined and is an array
    if (!Array.isArray(items)) {
        console.error('Expected items to be an array but received:', items);
        return <div>Error: Data not available</div>;
    }

    const headers = [
        { name: "KODE RS", className: "w-[7%]" },
        { name: "NAMA RUMAH SAKIT" },
        { name: "KOTA/KABUPATEN" },
        { name: "TAHUN", className: "text-center w-[9%]" },
        { name: "JENIS PENGUNJUNG", className: "w-[16%]" },
        { name: "JUMLAH PENGUNJUNG", className: "text-right w-[13%]" },
    ];


    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">LAPORAN RL 5.1</h1>
                            <p className="text-center pb-4">
                                <strong>Periode Tanggal: </strong>{formatDate(tgl_awal)} - {formatDate(tgl_akhir)}
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
                                            <TableRow key={data.KODE} isEven={index % 2 === 0}>
                                                <TableCell>{data.KODE}</TableCell>
                                                <TableCell>{data.NAMAINST}</TableCell>
                                                <TableCell className='uppercase'>{data.KOTA}</TableCell>
                                                <TableCell className='text-center'>{data.TAHUN}</TableCell>
                                                <TableCell>{data.DESKRIPSI}</TableCell>
                                                <TableCell className='text-right'>{data.JUMLAH} PASIEN</TableCell>
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
        </AuthenticatedLayout>
    );
}
