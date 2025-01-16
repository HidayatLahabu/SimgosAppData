import React from 'react';
import Card from "@/Components/Card";
import { UsersIcon, UserPlusIcon, UserGroupIcon, BoldIcon, DocumentTextIcon, DocumentCheckIcon, EyeDropperIcon, ArrowRightCircleIcon, ExclamationTriangleIcon, BeakerIcon } from '@heroicons/react/16/solid';

export default function TodayData({
    pendaftaran,
    kunjungan,
    konsul,
    mutasi,
    kunjunganBpjs,
    rencanaKontrol,
    laboratorium,
    radiologi,
    resep,
    pulang,
    auth,
}) {

    const isAdmin = auth?.user?.name?.includes("Admin");

    return (

        <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
            <h1 className="uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400">
                Input Data
            </h1>
            <div className="text-gray-900 dark:text-gray-100 w-full">
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2 mb-4">
                    <Card
                        href={isAdmin ? route("pendaftaran.index") : null}
                        title="PENDAFTARAN"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={pendaftaran}
                        description=''
                        iconColor="text-green-500"
                    />
                    <Card
                        href={isAdmin ? route("kunjungan.index") : null}
                        title="KUNJUNGAN"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={kunjungan}
                        description=''
                        icon={UsersIcon}
                        iconColor="text-yellow-500"
                    />
                    <Card
                        href={isAdmin ? route("konsul.index") : null}
                        title="KONSULTASI"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={konsul}
                        description=''
                        icon={UserPlusIcon}
                        iconColor="text-blue-500"
                    />
                    <Card
                        href={isAdmin ? route("mutasi.index") : null}
                        title="MUTASI"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={mutasi}
                        description=''
                        icon={UserGroupIcon}
                        iconColor="text-red-500"
                    />
                    <Card
                        href={isAdmin ? route("kunjunganBpjs.index") : null}
                        title="PASIEN BPJS"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={kunjunganBpjs}
                        description=''
                        icon={BoldIcon}
                        iconColor="text-lime-500"
                    />
                    <Card
                        href={isAdmin ? route("rekonBpjs.index") : null}
                        title="KONTROL HARI INI"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={rencanaKontrol}
                        description=''
                        icon={DocumentCheckIcon}
                        iconColor="text-violet-500"
                    />
                    <Card
                        href={isAdmin ? route("layananLab.index") : null}
                        title="LABORATORIUM"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={laboratorium}
                        description=''
                        icon={BeakerIcon}
                        iconColor="text-orange-500"
                    />
                    <Card
                        href={isAdmin ? route("layananRad.index") : null}
                        title="RADIOLOGI"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={radiologi}
                        description=''
                        icon={ExclamationTriangleIcon}
                        iconColor="text-amber-500"
                    />
                    <Card
                        href={isAdmin ? route("layananResep.index") : null}
                        title="FARMASI/RESEP"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={resep}
                        description=''
                        icon={DocumentTextIcon}
                        iconColor="text-cyan-500"
                    />
                    <Card
                        href={isAdmin ? route("layananPulang.index") : null}
                        title="PASIEN PULANG"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={pulang}
                        description=''
                        icon={ArrowRightCircleIcon}
                        iconColor="text-indigo-500"
                    />
                </div>
            </div>
        </div>

    );
}
