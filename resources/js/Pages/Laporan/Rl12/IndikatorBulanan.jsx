import React from 'react';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function IndikatorBulanan({
    tahunIni,
    statistikBulananIni,
    tahunLalu,
    statistikBulananLalu
}) {
    const bulanNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    const renderTable = (tahun, statistikBulanan) => (
        <Table>
            <TableHeader>
                <tr>
                    <TableHeaderCell>BULAN</TableHeaderCell>
                    <TableHeaderCell className="text-center w-[12%]">BOR</TableHeaderCell>
                    <TableHeaderCell className="text-center w-[12%]">AVLOS</TableHeaderCell>
                    <TableHeaderCell className="text-center w-[12%]">BTO</TableHeaderCell>
                    <TableHeaderCell className="text-center w-[12%]">TOI</TableHeaderCell>
                    <TableHeaderCell className="text-center w-[12%]">NDR</TableHeaderCell>
                    <TableHeaderCell className="text-center w-[12%]">GDR</TableHeaderCell>
                </tr>
            </TableHeader>
            <tbody>
                {statistikBulanan?.length > 0 ? (
                    statistikBulanan.map((data, index) => (
                        <TableRow key={`${data.TAHUN}-${data.BULAN}`} isEven={index % 2 === 0}>
                            <TableCell>{bulanNames[data.BULAN - 1]}</TableCell>
                            <TableCell className="text-center">{data.BOR}</TableCell>
                            <TableCell className="text-center">{data.AVLOS}</TableCell>
                            <TableCell className="text-center">{data.BTO}</TableCell>
                            <TableCell className="text-center">{data.TOI}</TableCell>
                            <TableCell className="text-center">{data.NDR}</TableCell>
                            <TableCell className="text-center">{data.GDR}</TableCell>
                        </TableRow>
                    ))
                ) : (
                    <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                        <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                    </tr>
                )}
            </tbody>
        </Table>
    );

    return (
        <div className="pb-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <h1 className="uppercase text-center font-extrabold text-xl text-indigo-700 dark:text-gray-200 mb-2">
                            Indikator Pelayanan Bulanan
                        </h1>
                        <div className="grid grid-cols-2 gap-5">
                            {/* Tabel Tahun Ini */}
                            <div>
                                <h2 className="text-center font-bold text-lg mb-2 text-indigo-700 dark:text-gray-200">
                                    Tahun {tahunIni}
                                </h2>
                                {renderTable(tahunIni, statistikBulananIni)}
                            </div>
                            {/* Tabel Tahun Lalu */}
                            <div>
                                <h2 className="text-center font-bold text-lg mb-2 text-indigo-700 dark:text-gray-200">
                                    Tahun {tahunLalu}
                                </h2>
                                {renderTable(tahunLalu, statistikBulananLalu)}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
