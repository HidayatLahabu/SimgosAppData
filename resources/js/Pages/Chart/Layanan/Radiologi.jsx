import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

// Mendaftarkan elemen yang digunakan
Chart.register(...registerables);

const Radiologi = ({ radiologiTahunIni, radiologiTahunLalu, tahunIni, tahunLalu }) => {
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
        const tahunIniCounts = Array(12).fill(0); // Default semua bulan = 0
        const tahunLaluCounts = Array(12).fill(0);

        radiologiTahunIni.forEach((item) => {
            tahunIniCounts[item.bulan - 1] = item.total; // bulan di index 0-11
        });

        radiologiTahunLalu.forEach((item) => {
            tahunLaluCounts[item.bulan - 1] = item.total;
        });

        // Chart untuk perbandingan antara tahun ini dan tahun lalu
        chartInstance = new Chart(ctx, {
            type: "bar",
            data: {
                labels: bulan,
                datasets: [
                    {
                        label: `Tahun ${tahunIni}`,
                        data: tahunIniCounts,
                        backgroundColor: "rgba(247, 247, 7)",
                        borderColor: "rgba(247, 247, 7)",
                        borderWidth: 1,
                    },
                    {
                        label: `Tahun ${tahunLalu}`,
                        data: tahunLaluCounts,
                        backgroundColor: "rgba(247, 247, 7, 0.4)",
                        borderColor: "rgba(247, 247, 7, 0.4)",
                        borderWidth: 1,
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
                barThickness: 30,
                grouped: true,
            },
        });

        return () => {
            if (chartInstance) chartInstance.destroy();
        };
    }, [radiologiTahunIni, radiologiTahunLalu]);

    return (
        <div className="p-5 flex flex-wrap w-full">
            <div className="w-full">
                <div className="max-w-full mx-auto w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <div>
                                <h1 className="uppercase text-center font-bold text-xl">Order Radiologi Tahun {tahunIni} dan {tahunLalu}</h1>
                                <canvas ref={chartRef}></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Radiologi;
