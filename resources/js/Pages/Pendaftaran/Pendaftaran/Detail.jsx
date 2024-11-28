import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/ButtonBack';
import ButtonPasien from '@/Components/ButtonPasien';
import ButtonBpjs from '@/Components/ButtonBpjs';
import DataKunjungan from '../Kunjungan/DataKunjungan';

export default function Detail({ auth, detail, dataKunjungan, nomorPendaftaran }) {
    // Generate detailData dynamically from the detail object
    const detailData = Object.keys(detail).map((key) => ({
        uraian: key,
        value: detail[key],
    }));

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
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">NO</th>
                                            <th className="px-3 py-2">COLUMN</th>
                                            <th className="px-3 py-2">VALUE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {detailData.map((detailItem, index) => (
                                            <tr key={index} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td className="px-3 py-3 w-16">{index + 1}</td>
                                                <td className="px-3 py-3 w-56">{detailItem.uraian}</td>
                                                <td className="px-3 py-3 break-words">
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
                                                    ) : detailItem.value}
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

            <DataKunjungan dataKunjungan={dataKunjungan} nomorPendaftaran={nomorPendaftaran} />

        </AuthenticatedLayout>
    );
}
