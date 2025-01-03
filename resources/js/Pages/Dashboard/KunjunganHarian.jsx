import React from 'react';
import CardKunjungan from "@/Components/CardKunjungan";

export default function KunjunganHarian({ statistikKunjungan }) {

    return (
        <div className="max-w-full mx-auto sm:px-5 lg:px- w-full">
            <h1 className="uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400 mb-2">
                Kunjungan
            </h1>
            <div className="text-gray-900 dark:text-gray-100 w-full">
                <div className="grid grid-cols-5 gap-2">
                    <CardKunjungan
                        title="Rawat Jalan"
                        value={statistikKunjungan.rajal}
                        date={statistikKunjungan.tanggalUpdated}
                    />
                    <CardKunjungan
                        title="Rawat Darurat"
                        value={statistikKunjungan.darurat}
                        date={statistikKunjungan.tanggalUpdated}
                    />
                    <CardKunjungan
                        title="Rawat Inap"
                        value={statistikKunjungan.ranap}
                        date={statistikKunjungan.tanggalUpdated}
                    />
                    <CardKunjungan
                        title="Laboratorium"
                        value={statistikKunjungan.laboratorium}
                        date={statistikKunjungan.updateLaboratorium}
                    />
                    <CardKunjungan
                        title="Radiologi"
                        value={statistikKunjungan.radiologi}
                        date={statistikKunjungan.updateRadiologi}
                    />
                </div>
            </div>
        </div>
    );
}
