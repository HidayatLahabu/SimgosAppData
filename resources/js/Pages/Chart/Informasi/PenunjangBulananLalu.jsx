import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

// Register chart elements
Chart.register(...registerables);

const PenunjangBulananLalu = ({ penunjangBulananLalu }) => {
    const chartRef = useRef(null);
    const chartInstanceRef = useRef(null);

    const currentYear = new Date().getFullYear();

    useEffect(() => {
        const ctx = chartRef.current.getContext("2d");

        // Hancurkan chart lama kalau ada
        if (chartInstanceRef.current) {
            chartInstanceRef.current.destroy();
        }

        // Ambil semua data yang jumlahnya > 0
        const filteredValidData = penunjangBulananLalu
            .filter(item => item.jumlah > 0)
            .sort((a, b) => b.jumlah - a.jumlah); // Urutkan dari jumlah terbesar ke terkecil

        // Ambil label dan nilai dari data yang sudah diurutkan
        const sortedLabels = filteredValidData.map(item => item.subUnit || "Tidak diketahui");
        const sortedRajalCounts = filteredValidData.map(item => item.jumlah);

        // Buat chart baru
        chartInstanceRef.current = new Chart(ctx, {
            type: "bar",
            data: {
                labels: sortedLabels,
                datasets: [
                    {
                        label: `${currentYear}`,
                        data: sortedRajalCounts,
                        backgroundColor: "rgba(192, 222, 24, 0.4)",
                        borderColor: "rgba(192, 222, 24, 0.4)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            color: 'rgb(176, 175, 153)',
                        },
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            color: 'rgb(176, 175, 153)',
                        },
                    },
                },
            },
        });

        // Cleanup saat unmount
        return () => {
            if (chartInstanceRef.current) {
                chartInstanceRef.current.destroy();
            }
        };
    }, [penunjangBulananLalu]);

    return (
        <div className="p-5 w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100">
                    <h1 className="uppercase text-center font-bold text-xl">Layanan Penunjang Per Unit Tahun Lalu</h1>
                    <canvas ref={chartRef}></canvas>
                </div>
            </div>
        </div>
    );
};

export default PenunjangBulananLalu;
