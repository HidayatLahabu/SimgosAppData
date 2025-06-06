import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

// Mendaftarkan elemen yang digunakan
Chart.register(...registerables);

const Rekon = ({ rekonTahunIni, rekonTahunLalu, tahunIni, tahunLalu }) => {
    const chartRef = useRef(null); // Referensi untuk chart
    let chartInstance = null;

    useEffect(() => {
        // Hancurkan chart sebelumnya jika ada
        if (chartInstance) {
            chartInstance.destroy();
        }

        const ctx = chartRef.current.getContext("2d");

        const bulan = [
            "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
            "Jul", "Agust", "Sept", "Okt", "Nov", "Des",
        ];

        // Map data untuk tahun ini dan tahun lalu
        const tahunIniCounts = Array(12).fill(0);
        const tahunLaluCounts = Array(12).fill(0);

        rekonTahunIni.forEach((item) => {
            tahunIniCounts[item.bulan - 1] = item.total;
        });

        rekonTahunLalu.forEach((item) => {
            tahunLaluCounts[item.bulan - 1] = item.total;
        });

        // Chart untuk perbandingan antara tahun ini dan tahun lalu
        chartInstance = new Chart(ctx, {
            type: "line",
            data: {
                labels: bulan,
                datasets: [
                    {
                        label: `${tahunLalu}`,
                        data: tahunLaluCounts,
                        borderColor: "rgba(11, 212, 155)",
                        backgroundColor: "rgba(11, 212, 155, 0.3)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: `${tahunIni}`,
                        data: tahunIniCounts,
                        borderColor: "rgba(12, 245, 54)",
                        backgroundColor: "rgba(12, 245, 54, 0.3)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'rgb(176, 175, 153)',
                        },
                    },
                    x: {
                        ticks: {
                            color: 'rgb(176, 175, 153)',
                        },
                    },
                },
            },
        });

        return () => {
            if (chartInstance) chartInstance.destroy();
        };
    }, [rekonTahunIni, rekonTahunLalu]);

    return (
        <div className="pl-5 pr-2 py-5 flex flex-col w-full h-full">
            <div className="w-full flex-1">
                <div className="max-w-full mx-auto w-full h-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full h-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full h-full">
                            <div>
                                <h1 className="uppercase text-center font-bold text-normal">Rencana Kontrol Tahun {tahunIni} dan {tahunLalu}</h1>
                                <canvas ref={chartRef}></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Rekon;
