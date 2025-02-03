import React from 'react';
import CardKunjungan from "@/Components/Card/CardKunjungan";
import { formatDate } from '@/utils/formatDate';

export default function KunjunganHarian({ statistikKunjungan }) {
    // Proses data untuk 5 hari terakhir
    const processChartData = (field) => {
        const labels = statistikKunjungan.lastFiveDaysData.map((item) => item.tanggal);
        const values = statistikKunjungan.lastFiveDaysData.map((item) => item[field]);
        return { labels, values };
    };

    const rajalChartData = processChartData("rajal");
    const daruratChartData = processChartData("darurat");
    const ranapChartData = processChartData("ranap");

    return (
        <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
            <div className="text-gray-900 dark:text-gray-100 w-full">
                <div className="grid grid-cols-1 gap-2 md:grid-cols-3">
                    <CardKunjungan
                        title="Rawat Jalan"
                        titleColor="rgba(109, 242, 97)"
                        date={statistikKunjungan.todayUpdated}
                        chartData={rajalChartData}
                        barColor="rgba(109, 242, 97, 0.6)"
                    />
                    <CardKunjungan
                        title="Rawat Darurat"
                        titleColor="rgba(245, 54, 121)"
                        date={statistikKunjungan.todayUpdated}
                        chartData={daruratChartData}
                        barColor="rgba(245, 54, 121, 0.6)"
                    />
                    <CardKunjungan
                        title="Rawat Inap"
                        titleColor="rgba(240, 240, 10)"
                        date={statistikKunjungan.todayUpdated}
                        chartData={ranapChartData}
                        barColor="rgba(240, 240, 10, 0.6)"
                    />
                </div>
            </div>
        </div>
    );
}
