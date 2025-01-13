import React, { useState } from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import RajalHarian from './RajalHarian';
import RanapHarian from './RanapHarian';
import RajalMingguan from './RajalMingguan';
import RanapMingguan from './RanapMingguan';
import RajalBulanan from './RajalBulanan';
import RanapBulanan from './RanapBulanan';
import RajalTahunan from './RajalTahunan';
import RanapTahunan from './RanapTahunan';
import Cetak from './Cetak';

export default function Index({
    auth,
    rajalHarian,
    ranapHarian,
    rajalMingguan,
    ranapMingguan,
    rajalBulanan,
    ranapBulanan,
    rajalTahunan,
    ranapTahunan,
}) {

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Informasi" />

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <RajalHarian rajalHarian={rajalHarian} />
                </div>
                <div className="w-1/2 pl-2">
                    <RanapHarian ranapHarian={ranapHarian} />
                </div>
            </div>

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <RajalMingguan rajalMingguan={rajalMingguan} />
                </div>
                <div className="w-1/2 pl-2">
                    <RanapMingguan ranapMingguan={ranapMingguan} />
                </div>
            </div>

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <RajalBulanan rajalBulanan={rajalBulanan} />
                </div>
                <div className="w-1/2 pl-2">
                    <RanapBulanan ranapBulanan={ranapBulanan} />
                </div>
            </div>

            <div className="p-5 flex flex-wrap w-full">
                <div className="w-1/2 pr-2">
                    <RajalTahunan rajalTahunan={rajalTahunan} />
                </div>
                <div className="w-1/2 pl-2">
                    <RanapTahunan ranapTahunan={ranapTahunan} />
                </div>
            </div>

            <div className="w-full">
                <Cetak
                />
            </div>
        </AuthenticatedLayout>
    );
}
