import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import DetailPoa from './DetailPoa';
import DetailPov from './DetailPov';
import ButtonBack from '@/Components/ButtonBack';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function Detail({ auth, detail, detailPoa, detailPov }) {

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

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Satusehat" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="relative flex items-center justify-between pb-2">
                                    <ButtonBack href={route("barangBza.index")} />
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">DATA DETAIL BARANG</h1>
                                </div>
                                <div className="flex space-x-4">
                                    <div className="flex-1">
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
                                                {detailData.map((detailItem, index) => (
                                                    <TableRow key={index} className="text-xs">
                                                        <TableCell>{index + 1}</TableCell>
                                                        <TableCell>{detailItem.uraian}</TableCell>
                                                        <TableCell>{detailItem.value}</TableCell>
                                                    </TableRow>
                                                ))}
                                            </tbody>
                                        </Table>
                                    </div>
                                    <div className="flex flex-col w-1/3">
                                        <DetailPoa detailPoa={detailPoa} />
                                    </div>
                                    <div className="flex flex-col w-1/3">
                                        <DetailPov detailPov={detailPov} />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

