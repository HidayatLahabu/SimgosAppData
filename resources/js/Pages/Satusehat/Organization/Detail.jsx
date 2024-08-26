import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";

export default function Detail({ auth, detail }) {
    // Prepare the data to be displayed in the desired format
    const encounterDetails = [
        { no: 1, uraian: 'ID', value: detail.id },
        { no: 2, uraian: 'IDENTIFIER', value: JSON.stringify(detail.identifier) },
        { no: 3, uraian: 'ACTIVE', value: detail.active },
        { no: 4, uraian: 'NAME', value: JSON.stringify(detail.name) },
        { no: 5, uraian: 'ALIAS', value: JSON.stringify(detail.alias) },
        { no: 6, uraian: 'TELECOM', value: JSON.stringify(detail.telecom) },
        { no: 7, uraian: 'ADDRESS', value: JSON.stringify(detail.address) },
        { no: 8, uraian: 'PART OF', value: JSON.stringify(detail.partOf) },
        { no: 9, uraian: 'REF ID', value: JSON.stringify(detail.refId) },
        { no: 10, uraian: 'SEND DATE', value: JSON.stringify(detail.sendDate) },
        { no: 11, uraian: 'FLAG', value: JSON.stringify(detail.flag) },
        { no: 12, uraian: 'SEND', value: detail.send },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Detail Encounter" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-xl pb-2">
                                    DATA DETAIL ENCOUNTER
                                </h1>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">NO</th>
                                            <th className="px-3 py-2">URAIAN</th>
                                            <th className="px-3 py-2">VALUE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {encounterDetails.map((detailItem, index) => (
                                            <tr key={index} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td className="px-3 py-3">{detailItem.no}</td>
                                                <td className="px-3 py-3 uppercase">{detailItem.uraian}</td>
                                                <td className="px-3 py-3">{detailItem.value}</td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
