import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/ButtonBack';
import ButtonDetail from "@/Components/ButtonDetail";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import TableCellMenu from "@/Components/TableCellMenu";
import InformasiKunjungan from "./InfoKunjungan";

export default function TableRme({ auth,
    dataTable,
    nomorKunjungan,
    nomorPendaftaran,
    namaPasien,
    normPasien,
    ruanganTujuan,
    statusKunjungan,
    tanggalKeluar,
    dpjp
}) {

    const headers = [
        { name: "NO" },
        { name: "NOMOR PENDAFTARAN" },
        { name: "ID DIAGNOSA" },
        { name: "TANGGAL", className: "text-center" },
        { name: "OLEH" },
        { name: "STATUS" },
        { name: "MENU", className: "text-center" },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Pendaftaran" />
            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="relative flex items-center justify-between pb-2">
                                    <ButtonBack href={route("kunjungan.detail", { id: nomorKunjungan })} />
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">DAFTAR DIAGNOSA</h1>
                                </div>

                                <InformasiKunjungan
                                    nomorPendaftaran={nomorPendaftaran}
                                    nomorKunjungan={nomorKunjungan}
                                    normPasien={normPasien}
                                    namaPasien={namaPasien}
                                    ruanganTujuan={ruanganTujuan}
                                    dpjp={dpjp}
                                    tanggalKeluar={tanggalKeluar}
                                    statusKunjungan={statusKunjungan}
                                />

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
                                        {dataTable.length > 0 ? (
                                            dataTable.map((item, index) => (
                                                <TableRow key={index}>
                                                    <TableCell>{index + 1}</TableCell>
                                                    <TableCell>{item.pendaftaran}</TableCell>
                                                    <TableCell>{item.id}</TableCell>
                                                    <TableCell className='text-center'>{item.tanggal}</TableCell>
                                                    <TableCell>{item.oleh}</TableCell>
                                                    <TableCell>
                                                        {item.status ? 'Selesai' : 'Sedang Dilayani'}
                                                    </TableCell>
                                                    <TableCellMenu>
                                                        {item.id ? (
                                                            <ButtonDetail href={route("kunjungan.detailDiagnosa", { id: item.id })} />
                                                        ) : (
                                                            <span className="text-gray-500">No detail available</span>
                                                        )}
                                                    </TableCellMenu>
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
        </AuthenticatedLayout>
    );
}