import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/Button/ButtonBack';
import TableSatusehat from "@/Components/Table/TableSatusehat";

export default function Detail({ auth, detail }) {

    const headers = [
        { name: "NO" },
        { name: "COLUMN NAME" },
        { name: "VALUE" },
    ];

    // Prepare the data to be displayed in the desired format
    const detailData = Object.keys(detail).map((key) => ({
        uraian: key,
        value: detail[key],
    }));

    // Specify how many rows per table
    const rowsPerTable = 10;

    // Split the data into groups
    const tables = [];
    for (let i = 0; i < detailData.length; i += rowsPerTable) {
        tables.push(detailData.slice(i, i + rowsPerTable));
    }

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="SatuSehat" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">

                                {/* HEADER ACTION AREA */}
                                <div className="relative flex items-center justify-between pb-2">
                                    <ButtonBack href={route("encounter.index")} />

                                    {/* TOMBOL BARU */}
                                    <div className="flex gap-2 ml-auto">
                                        <a
                                            href={route("patient.index")}
                                            className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow"
                                        >
                                            Patient
                                        </a>

                                        <a
                                            href={route("medication.index")}
                                            className="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow"
                                        >
                                            Medication
                                        </a>
                                    </div>

                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">
                                        DATA ENCOUNTER
                                    </h1>
                                </div>

                                <TableSatusehat
                                    headers={headers}
                                    tables={tables}
                                    rowsPerTable={rowsPerTable}
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
