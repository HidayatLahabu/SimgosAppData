import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/ButtonBack';
import DetailLoinc from './DetailLoinc';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function Detail({ auth, detail, detailLoinc }) {

    const headers = [
        { name: "NO", className: "w-[5%]" },
        { name: "COLUMN NAME", className: "w-[20%]" },
        { name: "VALUE", className: "w-[75%]" },
    ];
    // Generate detailData dynamically from the detail object
    const detailData = Object.keys(detail).map((key) => ({
        uraian: key, // Keep the original column name as it is
        value: detail[key],
    }));

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="SatuSehat" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="relative flex items-center justify-between pb-2">
                                    <ButtonBack href={route("tindakanToLoinc.index")} />
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">DATA DETAIL TINDAKAN</h1>
                                </div>
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
                        </div>
                    </div>
                </div>
            </div>

            <DetailLoinc detailLoinc={detailLoinc} />
        </AuthenticatedLayout>
    );
}
