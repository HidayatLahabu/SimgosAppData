import React from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import { formatNumber } from "@/utils/formatNumber";

export default function Bulanan({ statistikBulanan }) {

    const headers = [
        { name: "TAHUN", className: "w-[10%]" },
        { name: "BULAN", className: "w-[10%]" },
        { name: "Bed Turn Over", className: "text-center" },
        { name: "Average Length of Stay", className: "text-center" },
        { name: "Bed Turn Over", className: "text-center" },
        { name: "Turn Over Interval", className: "text-center" },
        { name: "Net Death Rate", className: "text-center" },
        { name: "Gross Death Rate", className: "text-center" },
    ];

    const bulanNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">
                                Indikator Pelayanan Bulanan
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
                                    {statistikBulanan.data.length > 0 ? (
                                        statistikBulanan.data.map((data, index) => (
                                            <TableRow key={`${data.TAHUN}-${data.BULAN}`} isEven={index % 2 === 0}>
                                                <TableCell>{data.TAHUN}</TableCell>
                                                <TableCell>{bulanNames[data.BULAN - 1]}</TableCell>
                                                <TableCell className="text-center">{data.BOR}</TableCell>
                                                <TableCell className="text-center">{data.AVLOS}</TableCell>
                                                <TableCell className="text-center">{formatNumber(data.BTO)}</TableCell>
                                                <TableCell className="text-center">{data.TOI}</TableCell>
                                                <TableCell className="text-center">{formatNumber(data.NDR)}</TableCell>
                                                <TableCell className="text-center">{formatNumber(data.GDR)}</TableCell>
                                            </TableRow>

                                        ))
                                    ) : (
                                        <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                            <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                        </tr>
                                    )}
                                </tbody>
                            </Table>
                            <Pagination links={statistikBulanan.links} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
