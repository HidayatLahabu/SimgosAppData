import React from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import { formatTime } from '@/utils/formatTime';

export default function Ratarata({ averageWaitData }) {

    const getNamaBulan = (bulan) => {
        const bulanIndo = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        return bulanIndo[bulan - 1]; // Mengembalikan nama bulan, bulan dimulai dari 1
    };

    const headers = [
        { name: "TAHUN", className: "w-[7%]" },
        { name: "BULAN", className: "w-[10%]" },
        { name: "RUANGAN RAWATAN", className: "w-[25%]" },
        { name: "NAMA DOKTER PELAKSANA LAYANAN" },
        { name: "RATA-RATA WAKTU TUNGGU", className: "w-[20%]" },
        { name: "JUMLAH PASIEN DILAYANI", className: "text-right w-[15%]" },
    ];

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">
                                RATA-RATA WAKTU TUNGGU RAWAT JALAN PER BULAN
                            </h1>
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
                                    {averageWaitData.data.length > 0 ? (
                                        averageWaitData.data.map((data, index) => (
                                            <TableRow
                                                key={data.DOKTER_REG}
                                                isEven={index % 2 === 0}
                                                className={parseFloat(data.AVERAGE_SELSIH) > 3600 ? 'text-red-500' : ''}
                                            >
                                                <TableCell>{data.TAHUN}</TableCell>
                                                <TableCell>{getNamaBulan(data.BULAN)}</TableCell>
                                                <TableCell>{data.UNITPELAYANAN}</TableCell>
                                                <TableCell>{data.DOKTER_REG}</TableCell>
                                                <TableCell>{formatTime(data.AVERAGE_SELSIH)}</TableCell>
                                                <TableCell className='text-right'>{data.JUMLAH_PASIEN}</TableCell>
                                            </TableRow>
                                        ))
                                    ) : (
                                        <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                            <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                        </tr>
                                    )}
                                </tbody>
                            </Table>
                            <Pagination links={averageWaitData.links} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
