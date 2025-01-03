import React from 'react';
import CardKunjungan from "@/Components/CardKunjungan";

export default function KunjunganHarian({ statistikKunjungan }) {
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
            {/* <h1 className="uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400 mb-2">
                Data Hari Ini <br /> <span className='text-lg'>{formattedDate}</span>
            </h1> */}
            <div className="text-gray-900 dark:text-gray-100 w-full">
                <div className="grid grid-cols-3 gap-2">
                    <CardKunjungan
                        title="Kunjungan Rawat Jalan"
                        value={statistikKunjungan.rajal}
                        date={statistikKunjungan.tanggalUpdated}
                    />
                    <CardKunjungan
                        title="Kunjungan Rawat Darurat"
                        value={statistikKunjungan.darurat}
                        date={statistikKunjungan.tanggalUpdated}
                    />
                    <CardKunjungan
                        title="Kunjungan Rawat Inap"
                        value={statistikKunjungan.ranap}
                        date={statistikKunjungan.tanggalUpdated}
                    />
                </div>
            </div>
        </div>
    );
}
