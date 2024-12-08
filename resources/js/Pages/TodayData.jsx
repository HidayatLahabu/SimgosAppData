import React from 'react';
import Card from "@/Components/Card";

export default function TodayData({
    pendaftaran,
    kunjungan,
    konsul,
    mutasi,
    kunjunganBpjs,
    laboratorium,
    radiologi,
    resep,
}) {

    const today = new Date();
    const formattedDate = today.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });

    return (
        <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="pt-5 pb-2 px-5 text-gray-900 dark:text-gray-100 w-full">
                    <h1 className="uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-gray-200 mb-4">
                        Data Hari Ini <br /> <span className='text-lg'>{formattedDate}</span>
                    </h1>
                    <div className="flex flex-wrap gap-4 justify-between mb-4">
                        <Card title="PENDAFTARAN" value={pendaftaran} />
                        <Card title="KUNJUNGAN" value={kunjungan} />
                        <Card title="KONSUL" value={konsul} />
                        <Card title="MUTASI" value={mutasi} />
                        <Card title="PASIEN BPJS" value={kunjunganBpjs} />
                        <Card title="LABORATORIUM" value={laboratorium} />
                        <Card title="RADIOLOGI" value={radiologi} />
                        <Card title="RESEP" value={resep} />
                    </div>
                </div>
            </div>
        </div>
    );
}
