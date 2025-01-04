import React from 'react';
import CardYear from "@/Components/CardYear";

export default function StatisticTahun({
    BOR,
    AVLOS,
    BTO,
    TOI,
    NDR,
    GDR,
    BOR_LALU,
    AVLOS_LALU,
    BTO_LALU,
    TOI_LALU,
    NDR_LALU,
    GDR_LALU,
}) {
    return (
        <div className="max-w-full mx-auto sm:pl-5 sm:pr-2 lg:px- w-full">
            <div className="text-gray-900 dark:text-gray-100 w-full">
                {/* Grid Container */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 mb-4">
                    {/* Card Items */}
                    <CardYear
                        title="BOR"
                        value={BOR}
                        lastValue={BOR_LALU}
                    />
                    <CardYear
                        title="AVLOS"
                        value={AVLOS}
                        lastValue={AVLOS_LALU}
                    />
                    <CardYear
                        title="BTO"
                        value={BTO}
                        lastValue={BTO_LALU}
                    />
                    <CardYear
                        title="TOI"
                        value={TOI}
                        lastValue={TOI_LALU}
                    />
                    <CardYear
                        title="NDR"
                        value={NDR}
                        lastValue={NDR_LALU}
                    />
                    <CardYear
                        title="GDR"
                        value={GDR}
                        lastValue={GDR_LALU}
                    />
                </div>
            </div>
        </div>
    );
}
