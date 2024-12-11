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
    pulang,
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
                        <Card title="PENDAFTARAN" titleSize="text-normal" valueSize="text-normal" value={pendaftaran} />
                        <Card title="KUNJUNGAN" titleSize="text-normal" valueSize="text-normal" value={kunjungan} />
                        <Card title="KONSUL" titleSize="text-normal" valueSize="text-normal" value={konsul} />
                        <Card title="MUTASI" titleSize="text-normal" valueSize="text-normal" value={mutasi} />
                        <Card title="BPJS" titleSize="text-normal" valueSize="text-normal" value={kunjunganBpjs} />
                        <Card title="LABORATORIUM" titleSize="text-normal" valueSize="text-normal" value={laboratorium} />
                        <Card title="RADIOLOGI" titleSize="text-normal" valueSize="text-normal" value={radiologi} />
                        <Card title="RESEP" titleSize="text-normal" valueSize="text-normal" value={resep} />
                        <Card title="PULANG" titleSize="text-normal" valueSize="text-normal" value={pulang} />
                    </div>
                </div>
            </div>
        </div>
    );
}
