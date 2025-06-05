import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const LaporanRL51 = ({ tahunIni, tahunLalu, laporanRl51, laporanRl51Lalu }) => {
    const chartRef = useRef(null);
    const chartInstance = useRef(null);

    useEffect(() => {
        if (!chartRef.current) return;
        if (chartInstance.current) chartInstance.current.destroy();

        const ctx = chartRef.current.getContext("2d");

        const groupDataByDeskripsi = (data) => {
            const result = {};
            (data || []).forEach(({ deskripsi, jumlah }) => {
                result[deskripsi] = Number(jumlah) || 0;
            });
            return result;
        };

        const dataTahunIni = groupDataByDeskripsi(laporanRl51);
        const dataTahunLalu = groupDataByDeskripsi(laporanRl51Lalu);

        const dataBaruTahunIni = dataTahunIni["Pengunjung Baru"] || 0;
        const dataLamaTahunIni = dataTahunIni["Pengunjung Lama"] || 0;
        const dataBaruTahunLalu = dataTahunLalu["Pengunjung Baru"] || 0;
        const dataLamaTahunLalu = dataTahunLalu["Pengunjung Lama"] || 0;

        const labels = ["Pengunjung Baru", "Pengunjung Lama"];

        const datasets = [
            {
                label: `Tahun ${tahunIni}`,
                data: [dataBaruTahunIni, dataLamaTahunIni],
                backgroundColor: "rgba(75, 192, 192, 1)",
                borderColor: "rgba(75, 192, 192, 1)",
                borderWidth: 1,
                categoryPercentage: 0.8,
                barPercentage: 0.9,
            },
            {
                label: `Tahun ${tahunLalu}`,
                data: [dataBaruTahunLalu, dataLamaTahunLalu],
                backgroundColor: "rgba(255, 99, 132, 0.7)",
                borderColor: "rgba(255, 99, 132, 0.7)",
                borderWidth: 1,
                categoryPercentage: 0.8,
                barPercentage: 0.9,
            }
        ];

        chartInstance.current = new Chart(ctx, {
            type: "bar",
            data: { labels, datasets },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "top",
                        labels: {
                            color: 'rgb(176, 175, 153)',
                            font: { size: 10 },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: "rgb(176, 175, 153)",
                            font: { size: 10 },
                        },
                    },
                    x: {
                        ticks: {
                            color: "rgb(176, 175, 153)",
                            font: { size: 10 },
                        },
                    },
                },
            },
        });

        return () => {
            if (chartInstance.current) chartInstance.current.destroy();
        };
    }, [laporanRl51, laporanRl51Lalu, tahunIni, tahunLalu]);

    return (
        <div className="p-5 flex flex-wrap w-full">
            <div className="w-full">
                <div className="max-w-full mx-auto w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <h1 className="uppercase text-center font-bold text-xl">Laporan RL 5.1 - PENGUNJUNG</h1>
                            <canvas ref={chartRef}></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default LaporanRL51;
