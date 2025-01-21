import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";
import { formatDate } from '@/utils/formatDate';

// Mendaftarkan elemen Chart.js
Chart.register(...registerables);

const RencanaKontrol = ({ rekonBpjs }) => {
    const chartRef = useRef(null);
    let chartInstance = null;

    useEffect(() => {
        // Hancurkan chart sebelumnya jika ada
        if (chartInstance) {
            chartInstance.destroy();
        }

        const ctx = chartRef.current.getContext("2d");

        const { direncanakan, berkunjungan, berhalangan } = rekonBpjs;

        chartInstance = new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: ["Direncanakan", "Berkunjung", "Berhalangan"],
                datasets: [
                    {
                        data: [direncanakan, berkunjungan, berhalangan],
                        backgroundColor: [
                            "rgba(54, 162, 235, 0.6)",
                            "rgba(255, 205, 86, 0.6)",
                            "rgba(255, 99, 132, 0.6)",
                        ],
                        borderColor: [
                            "rgba(54, 162, 235, 1)",
                            "rgba(255, 205, 86, 1)",
                            "rgba(255, 99, 132, 1)",
                        ],
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "top",
                        labels: {
                            color: "rgb(176, 175, 153)",
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) => {
                                const value = tooltipItem.raw;
                                return `${tooltipItem.label}: ${value} Pasien`;
                            },
                        },
                    },
                },
            },
        });

        return () => {
            if (chartInstance) chartInstance.destroy();
        };
    }, [rekonBpjs]);

    return (
        <div className="pr-5 pl-2 flex flex-wrap w-full">
            <div className="w-full">
                <div className="max-w-full mx-auto w-full">
                    <div className="bg-white bg-gradient-to-r from-indigo-800 to-indigo-900 text-center rounded-lg overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <div>
                                <h1 className="uppercase text-center font-bold text-lg">
                                    Rencana Kontrol <br /> {formatDate(rekonBpjs.tanggal)}
                                </h1>
                                <canvas ref={chartRef}></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default RencanaKontrol;
