import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";

export default function Detail({ auth, detail }) {
    // Prepare the data to be displayed in the desired format
    const detailData = [
        { uraian: 'ID', value: detail.id },
        { uraian: 'IDENTIFIER', value: JSON.stringify(detail.identifier) },
        { uraian: 'CLINICAL STATUS', value: detail.clinicalStatus },
        { uraian: 'VERIFICATION STATUS', value: JSON.stringify(detail.verificationStatus) },
        { uraian: 'CATEGORY', value: JSON.stringify(detail.category) },
        { uraian: 'SEVERITY', value: JSON.stringify(detail.severity) },
        { uraian: 'CODE', value: JSON.stringify(detail.code) },
        { uraian: 'BODY SITE', value: JSON.stringify(detail.bodySite) },
        { uraian: 'SUBJECT', value: JSON.stringify(detail.subject) },
        { uraian: 'ENCOUNTER', value: JSON.stringify(detail.encounter) },
        { uraian: 'REF ID', value: detail.refId },
        { uraian: 'NOPEN', value: detail.nopen },
        { uraian: 'SEND DATE', value: detail.sendDate },
        { uraian: 'SEND', value: detail.send },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="SatuSehat" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-xl pb-2">
                                    DATA DETAIL CONDITION
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
                                        {detailData.map((detailItem, index) => (
                                            <tr key={index} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td className="px-3 py-3 w-16">{index + 1}</td>
                                                <td className="px-3 py-3 w-56 uppercase">{detailItem.uraian}</td>
                                                <td className="px-3 py-3 break-words">{detailItem.value}</td>
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
