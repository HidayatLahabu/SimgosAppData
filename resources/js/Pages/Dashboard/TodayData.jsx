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
    auth, // pastikan auth diterima sebagai prop
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

    const isAdmin = auth?.user?.name?.includes("Admin");

    return (
        <div className="max-w-full mx-auto sm:px-5 lg:px- w-full">
            <div className="text-gray-900 dark:text-gray-100 w-full">
                <div className="flex flex-wrap gap-2 justify-between mb-4">
                    <Card
                        href={isAdmin ? route("pendaftaran.index") : null}
                        title="PENDAFTARAN"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={pendaftaran} />
                    <Card
                        href={isAdmin ? route("kunjungan.index") : null}
                        title="KUNJUNGAN"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={kunjungan} />
                    <Card
                        href={isAdmin ? route("konsul.index") : null}
                        title="KONSUL"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={konsul} />
                    <Card
                        href={isAdmin ? route("mutasi.index") : null}
                        title="MUTASI"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={mutasi} />
                    <Card
                        href={isAdmin ? route("kunjunganBpjs.index") : null}
                        title="BPJS"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={kunjunganBpjs} />
                    <Card
                        href={isAdmin ? route("layananLab.index") : null}
                        title="LABORATORIUM"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={laboratorium} />
                    <Card
                        href={isAdmin ? route("layananRad.index") : null}
                        title="RADIOLOGI"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={radiologi} />
                    <Card
                        href={isAdmin ? route("layananResep.index") : null}
                        title="RESEP"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={resep} />
                    <Card
                        href={isAdmin ? route("layananPulang.index") : null}
                        title="PULANG"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={pulang} />
                </div>
            </div>
        </div>
    );
}
