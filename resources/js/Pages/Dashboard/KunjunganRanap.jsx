import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

// Mendaftarkan elemen Chart.js
Chart.register(...registerables);

const KunjunganRanap = ({ kunjunganRanap }) => {
    const chartRef = useRef(null);
    let chartInstance = null;

    useEffect(() => {
        // Hancurkan chart sebelumnya jika ada
        if (chartInstance) {
            chartInstance.destroy();
        }

        const ctx = chartRef.current.getContext("2d");

        // Urutkan berdasarkan nilai MASUK 
        kunjunganRanap.sort((a, b) => b.MASUK - a.MASUK);

        // Ambil data deskripsi dan nilai dari kunjunganRanap
        const labels = kunjunganRanap.map((item) => item.DESKRIPSI);
        const awalData = kunjunganRanap.map((item) => item.AWAL);
        const masukData = kunjunganRanap.map((item) => item.MASUK);
        const keluarData = kunjunganRanap.map((item) => item.KELUAR);

        // Buat chart
        chartInstance = new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Awal",
                        data: awalData,
                        backgroundColor: "rgba(28, 185, 237, 0.6)",
                        borderColor: "rgba(28, 185, 237)",
                        borderWidth: 1,
                    },
                    {
                        label: "Masuk",
                        data: masukData,
                        backgroundColor: "rgba(245, 155, 66, 0.6)",
                        borderColor: "rgba(245, 155, 66)",
                        borderWidth: 1,
                    },
                    {
                        label: "Keluar",
                        data: keluarData,
                        backgroundColor: "rgba(66, 245, 99, 0.6)",
                        borderColor: "rgba(66, 245, 99)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                indexAxis: "y",
                scales: {
                    x: {
                        beginAtZero: true,
                        stacked: true,
                        ticks: {
                            color: "rgb(176, 175, 153)",
                        },
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            color: "rgb(176, 175, 153)",
                        },
                    },
                },
                plugins: {
                    legend: {
                        position: "top",
                        labels: {
                            color: "rgb(176, 175, 153)",
                        },
                    },
                },
            },
        });

        return () => {
            if (chartInstance) chartInstance.destroy();
        };
    }, [kunjunganRanap]);

    return (
        <div className="pl-5 pr-2 flex flex-wrap w-full">
            <div className="w-full">
                <div className="max-w-full mx-auto w-full">
                    <div className="bg-white bg-gradient-to-r from-indigo-800 to-indigo-900 text-center rounded-lg  overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <div>
                                <h1 className="uppercase text-center font-bold text-xl">Kunjungan Rawat Inap</h1>
                                <canvas ref={chartRef}></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default KunjunganRanap;
