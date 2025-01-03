import React, { useState } from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function Mingguan({ mingguan }) {

    const headers = [
        { name: "TAHUN", className: "w-[9%]" },
        { name: "MINGGU KE", className: "w-[10%]" },
        { name: "JENIS KUNJUNGAN", className: "w-[14%]" },
        { name: "INSTALASI" },
        { name: "UNIT", className: "w-[14%]" },
        { name: "SUB UNIT" },
        { name: "KUNJUNGAN", className: "text-right w-[10%]" },
        { name: "LAST UPDATED", className: "w-[12%]" },
    ];

    const [kunjunganMingguanLinks, setKunjunganMingguanLinks] = useState(mingguan.linksKunjunganMingguan);

    const handleKunjunganMingguanChange = (newLinks) => {
        setKunjunganMingguanLinks(newLinks);
    };

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">
                                Kunjungan Instalasi Penunjang Mingguan
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
                                    {Array.isArray(mingguan?.dataKunjunganMingguan) && mingguan.dataKunjunganMingguan.length > 0 ? (
                                        mingguan.dataKunjunganMingguan.map((data, index) => (
                                            <TableRow key={data.lastUpdated} isEven={index % 2 === 0}>
                                                <TableCell>{data.tahun}</TableCell>
                                                <TableCell>{data.minggu}</TableCell>
                                                <TableCell>{data.jenisKunjungan}</TableCell>
                                                <TableCell>{data.instalasi}</TableCell>
                                                <TableCell>{data.unit}</TableCell>
                                                <TableCell>{data.subUnit}</TableCell>
                                                <TableCell className='text-right'>{data.jumlah}</TableCell>
                                                <TableCell>{data.lastUpdated}</TableCell>
                                            </TableRow>
                                        ))
                                    ) : (
                                        <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                            <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                        </tr>
                                    )}
                                </tbody>
                            </Table>
                            <Pagination
                                links={kunjunganMingguanLinks}
                                onPageChange={handleKunjunganMingguanChange}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
