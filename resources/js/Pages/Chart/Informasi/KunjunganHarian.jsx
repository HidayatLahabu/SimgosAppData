import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

// Mendaftarkan elemen yang digunakan
Chart.register(...registerables);

const KunjunganHarian = ({ kunjunganHarian }) => {
    const chartRef = useRef(null);
    let chartInstance = null;

    useEffect(() => {
        if (chartInstance) {
            chartInstance.destroy();
        }

        const ctx = chartRef.current.getContext("2d");

        // Mengambil tanggal hari ini dan mengurangi 7 hari sebelumnya
        const today = new Date();
        const sevenDaysAgo = new Date();
        sevenDaysAgo.setDate(today.getDate() - 7); // Karena hari ini juga dihitung

        // Filter kunjunganHarian untuk hanya menyertakan 7 hari terakhir
        const filteredData = kunjunganHarian.filter(item => {
            const itemDate = new Date(item.tanggal);
            return itemDate >= sevenDaysAgo && itemDate <= today;
        });

        // Mengurutkan data berdasarkan tanggal
        filteredData.sort((a, b) => new Date(a.tanggal) - new Date(b.tanggal));

        const labels = filteredData.map(item => new Date(item.tanggal).toLocaleDateString("id-ID", { day: '2-digit', month: 'short' }));
        const rajalCounts = filteredData.map(item => item.rajal);
        const daruratCounts = filteredData.map(item => item.darurat);
        const ranapCounts = filteredData.map(item => item.ranap);

        chartInstance = new Chart(ctx, {
            type: "bar",
            data: {
                labels,
                datasets: [
                    {
                        label: "Rawat Jalan",
                        data: rajalCounts,
                        borderColor: "rgba(21, 73, 194)",
                        backgroundColor: "rgba(21, 73, 194)",
                        borderWidth: 1,
                    },
                    {
                        label: "Rawat Darurat",
                        data: daruratCounts,
                        borderColor: "rgba(245, 166, 7)",
                        backgroundColor: "rgba(245, 166, 7)",
                        borderWidth: 1,
                    },
                    {
                        label: "Rawat Inap",
                        data: ranapCounts,
                        borderColor: "rgba(7, 245, 166)",
                        backgroundColor: "rgba(7, 245, 166)",
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
            },
        });

        return () => {
            if (chartInstance) chartInstance.destroy();
        };
    }, [kunjunganHarian]);

    return (
        <div className="p-5 flex flex-wrap w-full">
            <div className="w-full">
                <div className="max-w-full mx-auto w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <div>
                                <h1 className="uppercase text-center font-bold text-xl">Kunjungan Harian</h1>
                                <canvas ref={chartRef}></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default KunjunganHarian;
