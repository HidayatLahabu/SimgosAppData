import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/Button/ButtonBack';
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import { formatDate } from '@/utils/formatDate';

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

    // Helper function for displaying status text
    const getStatusText = (uraian, value) => {
        if (uraian === "TEMPAT_LAHIR") {
            return value === 7501 ? "GORONTALO" : value;
        }

        if (uraian === "TANGGAL_LAHIR") {
            return formatDate(value);
        }

        return value;
    };

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
            <Head title="Master" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="relative flex items-center justify-between pb-2">
                                    <ButtonBack href={route("pasien.index")} />
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">DATA DETAIL PASIEN</h1>
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
                                                            <TableHeaderCell key={index} className={header.className || ""}>
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
                                                                {getStatusText(detailItem.uraian, detailItem.value)}
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
