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
    }) + ' ' + today.toLocaleTimeString('id-ID', {
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
    });

    return (
        <div className="max-w-full mx-auto sm:px-5 lg:px- w-full">
            <h1 className="uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400 mb-2">
                Data Hari Ini <br /> <span className='text-lg'>{formattedDate}</span>
            </h1>
            <div className="text-gray-900 dark:text-gray-100 w-full">
                <div className="flex flex-wrap gap-2 justify-between mb-4">
                    <Card
                        href={route("pendaftaran.index")}
                        title="PENDAFTARAN"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={pendaftaran} />
                    <Card href={route("kunjungan.index")}
                        title="KUNJUNGAN"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={kunjungan} />
                    <Card
                        href={route("konsul.index")}
                        title="KONSUL"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={konsul} />
                    <Card
                        href={route("mutasi.index")}
                        title="MUTASI"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={mutasi} />
                    <Card
                        href={route("kunjunganBpjs.index")}
                        title="BPJS"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={kunjunganBpjs} />
                    <Card
                        href={route("layananLab.index")}
                        title="LABORATORIUM"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={laboratorium} />
                    <Card
                        href={route("layananRad.index")}
                        title="RADIOLOGI"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={radiologi} />
                    <Card
                        href={route("layananResep.index")}
                        title="RESEP"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={resep} />
                    <Card
                        href={route("layananPulang.index")}
                        title="PULANG"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={pulang} />
                </div>
            </div>
        </div>
    );
}
