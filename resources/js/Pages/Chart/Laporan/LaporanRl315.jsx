import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const LaporanRl315 = ({ tahunIni, laporanRl315 }) => {
    const chartRef = useRef(null);
    const chartInstanceRef = useRef(null);

    useEffect(() => {
        const ctx = chartRef.current.getContext("2d");

        if (chartInstanceRef.current) {
            chartInstanceRef.current.destroy();
        }

        if (!laporanRl315 || laporanRl315.length === 0) return;

        // Filter data dengan total pasien lebih dari 100
        const filteredData = laporanRl315.filter((item) => {
            const totalPatients = item.RJ + item.LAB + item.RAD + item.JMLRI;
            return totalPatients > 100;  // Hanya data yang memiliki lebih dari 100 pasien
        });

        // Hitung total pasien untuk setiap item
        const totalPatientsPerItem = filteredData.map((item) => {
            return {
                ...item,
                total: item.RJ + item.LAB + item.rs + item.RAD + item.JMLRI,
            };
        });

        // Urutkan data berdasarkan total pasien tertinggi
        totalPatientsPerItem.sort((a, b) => b.total - a.total);

        // Sesuaikan labels dan datasets dengan urutan total pasien tertinggi
        const labels = totalPatientsPerItem.map((item) => item.deskripsi);
        const datasets = [
            {
                label: "Rawat Jalan",
                data: totalPatientsPerItem.map((item) => item.RJ),
                backgroundColor: "rgba(173, 255, 47, 0.8)", // Neon green (Chartreuse)
            },
            {
                label: "Rawat Inap",
                data: totalPatientsPerItem.map((item) => item.JMLRI),
                backgroundColor: "rgba(255, 105, 180, 0.8)", // Hot pink
            },
            {
                label: "Laboratorium",
                data: totalPatientsPerItem.map((item) => item.LAB),
                backgroundColor: "rgba(0, 255, 255, 0.8)", // Aqua / Cyan
            },
            {
                label: "Radiologi",
                data: totalPatientsPerItem.map((item) => item.RAD),
                backgroundColor: "rgba(218, 112, 214, 0.8)", // Orchid / Purple pink
            },
        ];


        // Filter dataset yang memiliki nilai lebih besar dari 0
        const filteredDatasets = datasets.filter((dataset) => {
            return dataset.data.some(value => value > 0); // Hanya dataset yang memiliki nilai lebih dari 0
        });

        chartInstanceRef.current = new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: filteredDatasets, // Gunakan filteredDatasets
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: "top",
                        labels: {
                            color: 'rgb(176, 175, 153)',
                            font: {
                                size: 10,
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            color: "rgb(176, 175, 153)",
                            font: {
                                size: 10,
                            },
                        },
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                    },
                },
            },
        });

        return () => {
            if (chartInstanceRef.current) {
                chartInstanceRef.current.destroy();
            }
        };
    }, [laporanRl315]);

    return (
        <div className="p-5 w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100">
                    <h1 className="uppercase text-center font-bold text-xl">Laporan RL 3.15 - Cara Bayar {tahunIni}</h1>
                    <canvas ref={chartRef}></canvas>
                </div>
            </div>
        </div>
    );
};

export default LaporanRl315;
