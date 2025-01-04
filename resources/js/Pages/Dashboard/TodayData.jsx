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
                        title="PENDAFTARAN PASIEN"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={pendaftaran}
                        description=''
                    />
                    <Card
                        href={isAdmin ? route("kunjungan.index") : null}
                        title="KUNJUNGAN PASIEN"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={kunjungan}
                        description=''
                    />
                    <Card
                        href={isAdmin ? route("konsul.index") : null}
                        title="PERMINTAAN KONSUL"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={konsul}
                        description=''
                    />
                    <Card
                        href={isAdmin ? route("mutasi.index") : null}
                        title="PERMINTAAN MUTASI"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={mutasi} />
                    <Card
                        href={isAdmin ? route("kunjunganBpjs.index") : null}
                        title="JAMINAN BPJS"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={kunjunganBpjs}
                        description=''
                    />
                    <Card
                        href={isAdmin ? route("layananLab.index") : null}
                        title="ORDER LABORATORIUM"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={laboratorium}
                        description=''
                    />
                    <Card
                        href={isAdmin ? route("layananRad.index") : null}
                        title="ORDER RADIOLOGI"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={radiologi}
                        description=''
                    />
                    <Card
                        href={isAdmin ? route("layananResep.index") : null}
                        title="ORDER OBAT/RESEP"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={resep}
                        description=''
                    />
                    <Card
                        href={isAdmin ? route("layananPulang.index") : null}
                        title="PASIEN PULANG"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={pulang}
                        description=''
                    />
                </div>
            </div>
        </div>
    );
}
