import React from 'react';
import CardYear from "@/Components/Card/CardYear";

export default function StatisticTahun({
    statistikTahunIni,
    statistikTahunLalu,
    statistikBulanIni,
    statistikBulanLalu,
}) {
    return (
        <div className="max-w-full mx-auto sm:pl-5 sm:pr-2 lg:px- w-full">
            <div className="text-gray-900 dark:text-gray-100 w-full">
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 mb-4">
                    <CardYear
                        title="Bed Occupancy Rate"
                        yearValue={statistikTahunIni.BOR}
                        lastYearValue={statistikTahunLalu.BOR}
                        monthValue={statistikBulanIni.BOR}
                        lastMonthValue={statistikBulanLalu.BOR}
                    />
                    <CardYear
                        title="Average Length of Stay"
                        yearValue={statistikTahunIni.AVLOS}
                        lastYearValue={statistikTahunLalu.AVLOS}
                        monthValue={statistikBulanIni.AVLOS}
                        lastMonthValue={statistikBulanLalu.AVLOS}
                    />
                    <CardYear
                        title="Bed Turn Over"
                        yearValue={statistikTahunIni.BTO}
                        lastYearValue={statistikTahunLalu.BTO}
                        monthValue={statistikBulanIni.BTO}
                        lastMonthValue={statistikBulanLalu.BTO}
                    />
                    <CardYear
                        title="Turn Over Interval"
                        yearValue={statistikTahunIni.TOI}
                        lastYearValue={statistikTahunLalu.TOI}
                        monthValue={statistikBulanIni.TOI}
                        lastMonthValue={statistikBulanLalu.TOI}
                    />
                    <CardYear
                        title="Net Death Rate"
                        yearValue={statistikTahunIni.NDR}
                        lastYearValue={statistikTahunLalu.NDR}
                        monthValue={statistikBulanIni.NDR}
                        lastMonthValue={statistikBulanLalu.NDR}
                    />
                    <CardYear
                        title="Gross Death Rate"
                        yearValue={statistikTahunIni.GDR}
                        lastYearValue={statistikTahunLalu.GDR}
                        monthValue={statistikBulanIni.GDR}
                        lastMonthValue={statistikBulanLalu.GDR}
                    />
                </div>
            </div>
        </div>
    );
}
