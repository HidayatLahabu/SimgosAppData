import React from 'react';
import CardMonth from "@/Components/CardMonth";

export default function StatisticBulan({
    statistikBulanIni,
    statistikBulanLalu,
}) {
    return (
        <div className="max-w-full mx-auto sm:pl-5 lg:pl-5 w-full">
            <div className="text-gray-900 dark:text-gray-100 w-full">
                {/* Grid Container */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 mb-4">
                    {/* Card Items */}
                    <CardMonth
                        title="BOR"
                        value={statistikBulanIni.BOR}
                        lastValue={statistikBulanLalu.BOR}
                    />
                    <CardMonth
                        title="AVLOS"
                        value={statistikBulanIni.AVLOS}
                        lastValue={statistikBulanLalu.AVLOS}
                    />
                    <CardMonth
                        title="BTO"
                        value={statistikBulanIni.BTO}
                        lastValue={statistikBulanLalu.BTO}
                    />
                    <CardMonth
                        title="TOI"
                        value={statistikBulanIni.TOI}
                        lastValue={statistikBulanLalu.TOI}
                    />
                    <CardMonth
                        title="NDR"
                        value={statistikBulanIni.NDR}
                        lastValue={statistikBulanLalu.NDR}
                    />
                    <CardMonth
                        title="GDR"
                        value={statistikBulanIni.GDR}
                        lastValue={statistikBulanLalu.GDR}
                    />
                </div>
            </div>
        </div>
    );
}
