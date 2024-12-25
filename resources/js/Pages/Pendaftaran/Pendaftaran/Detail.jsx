import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/ButtonBack';
import ButtonPasien from '@/Components/ButtonPasien';
import ButtonBpjs from '@/Components/ButtonBpjs';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function Detail({
    auth,
    detail,
}) {

    const headers = [
        { name: "NO" },
        { name: "COLUMN NAME" },
        { name: "VALUE" },
    ];

    // Generate detailData dynamically from the detail object
    const detailData = Object.keys(detail).map((key) => ({
        uraian: key,
        value: detail[key],
    }));

    // Filter out detailData with empty or whitespace values
    const filteredDetailData = detailData.filter((item) => {
        const value = String(item.value || "").trim(); // Convert value to string and trim whitespace
        return value !== ""; // Only include non-empty strings
    });

    // Specify how many rows per table
    const rowsPerTable = Math.ceil(filteredDetailData.length / 2);

    // Split the data into groups
    const tables = [];
    for (let i = 0; i < filteredDetailData.length; i += rowsPerTable) {
        tables.push(filteredDetailData.slice(i, i + rowsPerTable));
    }

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Pendaftaran" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="flex items-center justify-between pb-2">
                                    <h1 className="text-left uppercase font-bold text-2xl">
                                        DATA DETAIL PENDAFTARAN
                                    </h1>
                                    <div className="flex space-x-4">
                                        <ButtonPasien href={route("pendaftaran.pasien", { id: detail.NORM })} />
                                        {detail.NOMOR_ASURANSI && detail.NOMOR_ASURANSI.trim() !== "" && (
                                            <ButtonBpjs href={route("pendaftaran.bpjs", { id: detail.NORM })} />
                                        )}
                                        <ButtonBack href={route("pendaftaran.index")} />
                                    </div>
                                </div>
                                <div className="flex flex-wrap gap-2">
                                    {tables.map((tableData, tableIndex) => (
                                        <div
                                            key={tableIndex}
                                            className="flex-1 shadow-md rounded-lg"
                                        >
                                            <Table>
                                                <TableHeader>
                                                    <tr>
                                                        {headers.map((header, index) => (
                                                            <TableHeaderCell
                                                                key={index}
                                                                className={`${index === 0 ? 'w-[5%]' : index === 1 ? 'w-[40%]' : 'w-[auto]'} ${header.className || ""}`}
                                                            >
                                                                {header.name}
                                                            </TableHeaderCell>
                                                        ))}
                                                    </tr>
                                                </TableHeader>
                                                <tbody>
                                                    {tableData.map((detailItem, index) => (
                                                        <TableRow key={index}>
                                                            <TableCell>{index + 1 + tableIndex * rowsPerTable}</TableCell>
                                                            <TableCell>{detailItem.uraian}</TableCell>
                                                            <TableCell className="text-wrap">
                                                                {detailItem.uraian === "STATUS_PENDAFTARAN" ? (
                                                                    detailItem.value === 0 ? "Batal" :
                                                                        detailItem.value === 1 ? "Aktif" :
                                                                            detailItem.value === 2 ? "Selesai" :
                                                                                detailItem.value
                                                                ) : detailItem.uraian === "STATUS_PASIEN" ? (
                                                                    detailItem.value === 0 ? "Dibatalkan/Tidak Aktif" :
                                                                        detailItem.value === 1 ? "Aktif" :
                                                                            detailItem.value === 2 ? "Meninggal" :
                                                                                detailItem.value
                                                                ) : detailItem.uraian === "CONSENT_SATUSEHAT" ? (
                                                                    detailItem.value === 0 ? "Tidak Disetujui" :
                                                                        detailItem.value === 1 ? "Disetujui" :
                                                                            detailItem.value
                                                                ) : detailItem.uraian === "STATUS_DIAGNOSA" ? (
                                                                    detailItem.value === 0 ? "Batal" :
                                                                        detailItem.value === 1 ? "Final" :
                                                                            detailItem.value
                                                                ) : detailItem.value}
                                                            </TableCell>
                                                        </TableRow>
                                                    ))}
                                                </tbody>
                                            </Table>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
