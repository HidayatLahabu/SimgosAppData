import React, { useMemo } from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/ButtonBack';

export default function Detail({ auth, detail }) {
    // Generate detailData dynamically using useMemo to avoid unnecessary re-calculations
    const detailData = useMemo(() => Object.keys(detail).map((key) => ({
        uraian: key,
        value: detail[key],
    })), [detail]);

    // Helper function for displaying status text
    const getStatusText = (uraian, value) => {
        if (uraian === "STATUS_KUNJUNGAN") {
            return value === 1 ? "Baru" : value === 0 ? "Lama" : value;
        } else if (uraian === "STATUS_AKTIFITAS_KUNJUNGAN") {
            return value === 0 ? "Batal Kunjungan" : value === 1 ? "Sedang dilayani" : value === 2 ? "Selesai" : value;
        }
        return value;
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Pendaftaran" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="relative flex items-center justify-between pb-2">
                                    <ButtonBack href={route("pendaftaran.index")} />
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">
                                        Data Detail Kunjungan
                                    </h1>
                                </div>
                                <table className="w-full text-sm text-left text-gray-500 dark:text-gray-200 dark:bg-indigo-900 overflow-x-auto">
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">No</th>
                                            <th className="px-3 py-2">Column</th>
                                            <th className="px-3 py-2">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {detailData.map((detailItem, index) => (
                                            <tr key={index} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td className="px-3 py-3 w-16">{index + 1}</td>
                                                <td className="px-3 py-3 w-56">{detailItem.uraian}</td>
                                                <td className="px-3 py-3 break-words">
                                                    {getStatusText(detailItem.uraian, detailItem.value)}
                                                </td>
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
