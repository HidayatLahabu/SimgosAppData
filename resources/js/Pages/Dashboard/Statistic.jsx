import React from 'react';
import CardDashboard from "@/Components/CardDashboard";

export default function Statistic({
    BOR,
    AVLOS,
    BTO,
    TOI,
    NDR,
    GDR,
}) {

    return (
        <div className="max-w-full mx-auto sm:px-5 lg:px- w-full">
            <div className="text-gray-900 dark:text-gray-100 w-full">
                <div className="flex flex-wrap gap-2 justify-between mb-4">
                    <CardDashboard
                        title="Bed Occupancy Rate"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={BOR} />
                    <CardDashboard
                        title="Length of Stay"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={AVLOS} />
                    <CardDashboard
                        title="Bed Turn Over"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={BTO} />
                    <CardDashboard
                        title="Turn Over Interval"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={TOI} />
                    <CardDashboard
                        title="Net Death Rate"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={NDR} />
                    <CardDashboard
                        title="Gross Death Rate"
                        titleSize="text-normal"
                        valueSize="text-normal"
                        value={GDR} />
                </div>
            </div>
        </div>
    );
}
