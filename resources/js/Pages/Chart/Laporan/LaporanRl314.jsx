import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const LaporanRl314 = ({ tahunIni, laporanRl314 }) => {
    const chartRef = useRef(null);
    const chartInstanceRef = useRef(null);

    useEffect(() => {
        const ctx = chartRef.current.getContext("2d");

        if (chartInstanceRef.current) {
            chartInstanceRef.current.destroy();
        }

        if (!laporanRl314 || laporanRl314.length === 0) return;

        // Filter data dengan total pasien lebih dari 100
        const filteredData = laporanRl314.filter((item) => {
            const totalPatients = item.puskesmas + item.faskes + item.rs + item.kembaliPuskesmas + item.kembaliFaskes + item.kembaliRs + item.pasienRujukan + item.datangSendiri + item.diterimaKembali;
            return totalPatients > 100;  // Hanya data yang memiliki lebih dari 100 pasien
        });

        // Hitung total pasien untuk setiap item
        const totalPatientsPerItem = filteredData.map((item) => {
            return {
                ...item,
                total: item.puskesmas + item.faskes + item.rs + item.kembaliPuskesmas + item.kembaliFaskes + item.kembaliRs + item.pasienRujukan + item.datangSendiri + item.diterimaKembali,
            };
        });

        // Urutkan data berdasarkan total pasien tertinggi
        totalPatientsPerItem.sort((a, b) => b.total - a.total);

        // Sesuaikan labels dan datasets dengan urutan total pasien tertinggi
        const labels = totalPatientsPerItem.map((item) => item.deskripsi);
        const datasets = [
            {
                label: "Puskesmas",
                data: totalPatientsPerItem.map((item) => item.puskesmas),
                backgroundColor: "rgba(255, 99, 132, 1)",
            },
            {
                label: "Faskes",
                data: totalPatientsPerItem.map((item) => item.faskes),
                backgroundColor: "rgba(54, 162, 235, 1)",
            },
            {
                label: "RS",
                data: totalPatientsPerItem.map((item) => item.rs),
                backgroundColor: "rgba(75, 192, 192, 1)",
            },
            {
                label: "Kembali Puskesmas",
                data: totalPatientsPerItem.map((item) => item.kembaliPuskesmas),
                backgroundColor: "rgba(153, 102, 255, 1)",
            },
            {
                label: "Kembali Faskes",
                data: totalPatientsPerItem.map((item) => item.kembaliFaskes),
                backgroundColor: "rgba(255, 159, 64, 1)",
            },
            {
                label: "Kembali RS",
                data: totalPatientsPerItem.map((item) => item.kembaliRs),
                backgroundColor: "rgba(255, 205, 86, 1)",
            },
            {
                label: "Pasien Rujukan",
                data: totalPatientsPerItem.map((item) => item.pasienRujukan),
                backgroundColor: "rgba(201, 203, 207, 1)",
            },
            {
                label: "Datang Sendiri",
                data: totalPatientsPerItem.map((item) => item.datangSendiri),
                backgroundColor: "rgba(100, 149, 237, 1)",
            },
            {
                label: "Diterima Kembali",
                data: totalPatientsPerItem.map((item) => item.diterimaKembali),
                backgroundColor: "rgba(60, 179, 113, 1)",
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
                indexAxis: "y", // horizontal
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
                        beginAtZero: true,
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            color: "rgb(176, 175, 153)",
                            font: {
                                size: 10,
                            },
                        },
                    },
                },
            },
        });

        return () => {
            if (chartInstanceRef.current) {
                chartInstanceRef.current.destroy();
            }
        };
    }, [laporanRl314]);

    return (
        <div className="p-5 w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100">
                    <h1 className="uppercase text-center font-bold text-xl">Laporan RL 3.14 - Rujukan {tahunIni}</h1>
                    <canvas ref={chartRef}></canvas>
                </div>
            </div>
        </div>
    );
};

export default LaporanRl314;
