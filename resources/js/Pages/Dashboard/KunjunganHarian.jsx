import React from 'react';
import CardKunjungan from "@/Components/CardKunjungan";

export default function KunjunganHarian({ statistikKunjungan }) {

    return (
        <div className="max-w-full mx-auto sm:px-5 lg:px- w-full">
            <h1 className="uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400 mb-2">
                Informasi Kunjungan
            </h1>
            <div className="text-gray-900 dark:text-gray-100 w-full">
                <div className="grid grid-cols-5 gap-2">
                    <CardKunjungan
                        title="Rawat Jalan"
                        todayValue={statistikKunjungan.todayRajal}
                        date={statistikKunjungan.todayUpdated}
                        yesterdayValue={statistikKunjungan.yesterdayRajal}
                        yesterdayDate={statistikKunjungan.yesterdayUpdated}
                    />
                    <CardKunjungan
                        title="Rawat Darurat"
                        todayValue={statistikKunjungan.todayDarurat}
                        date={statistikKunjungan.todayUpdated}
                        yesterdayValue={statistikKunjungan.yesterdayDarurat}
                        yesterdayDate={statistikKunjungan.yesterdayUpdated}
                    />
                    <CardKunjungan
                        title="Rawat Inap"
                        todayValue={statistikKunjungan.todayRanap}
                        date={statistikKunjungan.todayUpdated}
                        yesterdayValue={statistikKunjungan.yesterdayRanap}
                        yesterdayDate={statistikKunjungan.yesterdayUpdated}
                    />
                    <CardKunjungan
                        title="Laboratorium"
                        todayValue={statistikKunjungan.todayLabData}
                        date={statistikKunjungan.todayLabUpdate}
                        yesterdayValue={statistikKunjungan.yesterdayLabData}
                        yesterdayDate={statistikKunjungan.yesterdayUpdateLab}
                    />
                    <CardKunjungan
                        title="Radiologi"
                        todayValue={statistikKunjungan.todayRadiologi}
                        date={statistikKunjungan.todayRadiologiUpdate}
                        yesterdayValue={statistikKunjungan.yesterdayRadiologi}
                        yesterdayDate={statistikKunjungan.yesterdayRadiologiUpdate}
                    />
                </div>
            </div>
        </div>
    );
}
