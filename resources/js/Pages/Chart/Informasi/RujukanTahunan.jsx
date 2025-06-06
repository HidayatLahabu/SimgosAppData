import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

// Register chart elements
Chart.register(...registerables);

const RujukanTahunan = ({ rujukanTahunan }) => {
    const chartRef = useRef(null);
    let chartInstance = null;

    useEffect(() => {
        if (chartInstance) {
            chartInstance.destroy();
        }

        const ctx = chartRef.current.getContext("2d");

        const filteredData = rujukanTahunan
            .filter(item => (item.masuk > 0 || item.keluar > 0 || item.balik > 0))
            .slice(0, 5)
            .reverse();

        const labels = filteredData.map(item => `${item.tahun}`);
        const rajalCounts = filteredData.map(item => item.masuk);
        const daruratCounts = filteredData.map(item => item.keluar);
        const ranapCounts = filteredData.map(item => item.balik);

        chartInstance = new Chart(ctx, {
            type: "bar",
            data: {
                labels,
                datasets: [
                    {
                        label: "Rujukan Masuk",
                        data: rajalCounts,
                        borderColor: "rgba(105, 21, 194)",
                        backgroundColor: "rgba(105, 21, 194)",
                        borderWidth: 1,
                    },
                    {
                        label: "Rujukan Keluar",
                        data: daruratCounts,
                        borderColor: "rgba(182, 194, 21)",
                        backgroundColor: "rgba(182, 194, 21)",
                        borderWidth: 1,
                    },
                    {
                        label: "Rujukan Balik",
                        data: ranapCounts,
                        borderColor: "rgba(194, 21, 133)",
                        backgroundColor: "rgba(194, 21, 133)",
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
    }, [rujukanTahunan]);

    return (
        <div className="p-5 flex flex-wrap w-full">
            <div className="w-full">
                <div className="max-w-full mx-auto w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <div>
                                <h1 className="uppercase text-center font-bold text-xl">Rujukan Tahunan</h1>
                                <canvas ref={chartRef}></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default RujukanTahunan;
