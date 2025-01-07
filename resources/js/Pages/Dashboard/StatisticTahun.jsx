import React from 'react';
import CardYear from "@/Components/CardYear";

export default function StatisticTahun({
    statistikTahunIni,
    statistikTahunLalu,
}) {
    return (
        <div className="max-w-full mx-auto sm:pl-5 sm:pr-2 lg:px- w-full">
            <div className="text-gray-900 dark:text-gray-100 w-full">
                {/* Grid Container */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 mb-4">
                    {/* Card Items */}
                    <CardYear
                        title="BOR"
                        value={statistikTahunIni.BOR}
                        lastValue={statistikTahunLalu.BOR}
                    />
                    <CardYear
                        title="AVLOS"
                        value={statistikTahunIni.AVLOS}
                        lastValue={statistikTahunLalu.AVLOS}
                    />
                    <CardYear
                        title="BTO"
                        value={statistikTahunIni.BTO}
                        lastValue={statistikTahunLalu.BTO}
                    />
                    <CardYear
                        title="TOI"
                        value={statistikTahunIni.TOI}
                        lastValue={statistikTahunLalu.TOI}
                    />
                    <CardYear
                        title="NDR"
                        value={statistikTahunIni.NDR}
                        lastValue={statistikTahunLalu.NDR}
                    />
                    <CardYear
                        title="GDR"
                        value={statistikTahunIni.GDR}
                        lastValue={statistikTahunLalu.GDR}
                    />
                </div>
            </div>
        </div>
    );
}
