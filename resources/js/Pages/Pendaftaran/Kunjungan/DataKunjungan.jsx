import React from 'react';
import ButtonDetail from "@/Components/ButtonDetail";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function DataKunjungan({ nomorPendaftaran, dataKunjungan = {} }) {
    const headers = [
        { name: "NO" },
        { name: "NOMOR KUNJUNGAN" },
        { name: "NOMOR PENDAFTARAN" },
        { name: "TANGGAL MASUK", className: "text-center" },
        { name: "TANGGAL KELUAR", className: "text-center" },
        { name: "RUANGAN TUJUAN" },
        { name: "STATUS" },
        { name: "MENU", className: "text-center" },
    ];

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DAFTAR KUNJUNGAN <br />DENGAN NOMOR PENDAFTARAN : {nomorPendaftaran}
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
                                    {dataKunjungan.length > 0 ? (
                                        dataKunjungan.map((kunjungan, index) => (
                                            <TableRow key={index}>
                                                <TableCell>{index + 1}</TableCell>
                                                <TableCell>{kunjungan.nomor}</TableCell>
                                                <TableCell>{kunjungan.pendaftaran}</TableCell>
                                                <TableCell className='text-center'>{kunjungan.masuk}</TableCell>
                                                <TableCell className='text-center'>{kunjungan.keluar}</TableCell>
                                                <TableCell>{kunjungan.ruangan}</TableCell>
                                                <TableCell>
                                                    {kunjungan.keluar ? 'Selesai' : 'Sedang Dilayani'}
                                                </TableCell>
                                                <td className="px-3 py-3">
                                                    {kunjungan.nomor ? (
                                                        <ButtonDetail href={route("kunjungan.tableRme", { id: kunjungan.nomor })} />
                                                    ) : (
                                                        <span className="text-gray-500">No detail available</span>
                                                    )}
                                                </td>
                                            </TableRow>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="8" className="text-center px-3 py-3">
                                                No data available
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
    );
}
