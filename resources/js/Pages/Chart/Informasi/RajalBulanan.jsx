import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

// Register chart elements
Chart.register(...registerables);

const RajalBulanan = ({ rajalBulanan }) => {
    const chartRef = useRef(null);
    const chartInstanceRef = useRef(null);

    const currentYear = new Date().getFullYear();

    useEffect(() => {
        const ctx = chartRef.current.getContext("2d");

        // Hancurkan chart lama kalau ada
        if (chartInstanceRef.current) {
            chartInstanceRef.current.destroy();
        }

        // Persiapkan data
        const filteredData = rajalBulanan.slice(0, 12).reverse();
        const filteredValidData = filteredData.filter(item => item.jumlah > 0);
        const labels = filteredValidData.map(item => item.subUnit || "Tidak diketahui");
        const rajalCounts = filteredValidData.map(item => item.jumlah);

        // Urutkan berdasarkan jumlah (rajalCounts) secara menurun
        const sortedData = filteredValidData
            .map((item, index) => ({ label: labels[index], rajalCount: rajalCounts[index] }))
            .sort((a, b) => b.rajalCount - a.rajalCount); // Urutkan dari terbesar ke terkecil

        // Ambil label dan jumlah yang sudah diurutkan
        const sortedLabels = sortedData.map(item => item.label);
        const sortedRajalCounts = sortedData.map(item => item.rajalCount);

        // Buat chart baru
        chartInstanceRef.current = new Chart(ctx, {
            type: "bar",
            data: {
                labels: sortedLabels,
                datasets: [
                    {
                        label: `${currentYear}`,
                        data: sortedRajalCounts,
                        backgroundColor: "rgba(21, 73, 194)",
                        borderColor: "rgba(21, 73, 194)",
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
    }, [rajalBulanan]);

    return (
        <div className="p-5 w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100">
                    <h1 className="uppercase text-center font-bold text-xl">Kunjungan Rawat Jalan Tahun Ini</h1>
                    <canvas ref={chartRef}></canvas>
                </div>
            </div>
        </div>
    );
};

export default RajalBulanan;
