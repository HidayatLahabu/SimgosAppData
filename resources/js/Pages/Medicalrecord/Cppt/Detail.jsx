import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/ButtonBack';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function Detail({ auth, detail }) {

    const headers = [
        { name: "NO" },
        { name: "COLUMN NAME" },
        { name: "VALUE" },
    ];

    // Generate detailData dynamically from the detail object
    const detailData = Object.keys(detail).map((key) => ({
        uraian: key, // Keep the original column name as it is
        value: detail[key],
    }));

    // Filter out detailData with empty or whitespace values
    const filteredDetailData = detailData.filter((item) => {
        const value = String(item.value || "").trim();
        return value !== "" && value !== "0";
    });

    // Tentukan jumlah row per tabel
    const rowsPerTable =
        filteredDetailData.length > 10
            ? Math.ceil(filteredDetailData.length / 2)
            : filteredDetailData.length;

    // Bagi data menjadi grup hanya jika jumlah row > 10
    const tables = filteredDetailData.length > 10 ? Array.from({
        length: Math.ceil(filteredDetailData.length / rowsPerTable),
    }, (_, i) => filteredDetailData.slice(i * rowsPerTable, (i + 1) * rowsPerTable))
        : [filteredDetailData];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Medicalrecord" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="relative flex items-center justify-between pb-2">
                                    <ButtonBack href={route("cppt.index")} />
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">DATA DETAIL CPPT</h1>
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
                                                            <TableHeaderCell key={index} className={`${index === 0 ? "w-[5%]" : index === 1 ? "w-[15%]" : "w-[auto]"} 
                                                            ${header.className || ""}`}
                                                            >
                                                                {
                                                                    header.name
                                                                }
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
                                                                {detailItem.uraian === "STATUS" ? (
                                                                    detailItem.value == 0 ? "Belum Final" :
                                                                        detailItem.value == 1 ? "Final" :
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