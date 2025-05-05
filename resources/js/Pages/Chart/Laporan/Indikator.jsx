import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

// Register chart.js components
Chart.register(...registerables);

const IndikatorPelayanan = ({ indikatorPelayanan }) => {
    const chartRef = useRef(null);
    const chartInstanceRef = useRef(null); // simpan instance Chart

    const bulan = [
        "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
        "Jul", "Agust", "Sept", "Okt", "Nov", "Des",
    ];

    useEffect(() => {

        if (chartInstanceRef.current) {
            chartInstanceRef.current.destroy();
        }

        const ctx = chartRef.current.getContext("2d");

        const filteredData = indikatorPelayanan
            .sort((a, b) => new Date(a.bulan) - new Date(b.bulan)); // Sort by date ascending

        const labels = filteredData.map(item => {
            const bulanIndex = new Date(item.bulan).getMonth(); // Ambil bulan dari date
            return bulan[bulanIndex]; // Ambil nama bulan dari array bulan
        });

        const bor = filteredData.map(item => item.bor);
        const avlos = filteredData.map(item => item.avlos);
        const bto = filteredData.map(item => item.bto);
        const toi = filteredData.map(item => item.toi);
        const ndr = filteredData.map(item => item.ndr);
        const gdr = filteredData.map(item => item.gdr);

        chartInstanceRef.current = new Chart(ctx, {
            type: "bar",
            data: {
                labels,
                datasets: [
                    {
                        label: "BOR",
                        data: bor,
                        backgroundColor: "rgba(54, 162, 235, 0.7)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 1,
                    },
                    {
                        label: "AVLOS",
                        data: avlos,
                        backgroundColor: "rgba(255, 206, 86, 0.7)",
                        borderColor: "rgba(255, 206, 86, 1)",
                        borderWidth: 1,
                    },
                    {
                        label: "BTO",
                        data: bto,
                        backgroundColor: "rgba(75, 192, 192, 0.7)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 1,
                    },
                    {
                        label: "TOI",
                        data: toi,
                        backgroundColor: "rgba(153, 102, 255, 0.7)",
                        borderColor: "rgba(153, 102, 255, 1)",
                        borderWidth: 1,
                    },
                    {
                        label: "NDR",
                        data: ndr,
                        backgroundColor: "rgba(255, 99, 132, 0.7)",
                        borderColor: "rgba(255, 99, 132, 1)",
                        borderWidth: 1,
                    },
                    {
                        label: "GDR",
                        data: gdr,
                        backgroundColor: "rgba(255, 159, 64, 0.7)",
                        borderColor: "rgba(255, 159, 64, 1)",
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
                            color: 'rgb(176, 175, 153)',
                            font: {
                                size: 10, // Ukuran font lebih kecil
                            },
                        },
                    },
                },
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
            if (chartInstanceRef.current) {
                chartInstanceRef.current.destroy();
            }
        };
    }, [indikatorPelayanan]);


    return (
        <div className="p-5 flex flex-wrap w-full">
            <div className="w-full">
                <div className="max-w-full mx-auto w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <div>
                                <h1 className="uppercase text-center font-bold text-xl">Indikator Pelayanan</h1>
                                <canvas ref={chartRef}></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default IndikatorPelayanan;
