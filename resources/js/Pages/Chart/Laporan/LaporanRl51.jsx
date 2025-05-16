import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const LaporanRL51 = ({ tahunIni, tahunLalu, laporanRl51, laporanRl51Lalu }) => {
    const chartRef = useRef(null);
    const chartInstance = useRef(null);

    useEffect(() => {
        if (!chartRef.current) return;
        if (chartInstance.current) chartInstance.current.destroy();

        const ctx = chartRef.current.getContext("2d");

        // Fungsi bantu untuk mengelompokkan data berdasarkan DESKRIPSI
        // Menghasilkan objek { DESKRIPSI: JUMLAH }
        const groupDataByDeskripsi = (data) => {
            const result = {};
            (data || []).forEach(({ deskripsi, jumlah }) => {
                result[deskripsi] = Number(jumlah) || 0;
            });
            return result;
        };

        const dataTahunIni = groupDataByDeskripsi(laporanRl51);
        const dataTahunLalu = groupDataByDeskripsi(laporanRl51Lalu);

        // Gabungkan semua label unik dari dua tahun
        const labels = Array.from(new Set([...Object.keys(dataTahunIni), ...Object.keys(dataTahunLalu)]));

        // Map data sesuai label
        const dataTahunIniArr = labels.map(label => dataTahunIni[label] || 0);
        const dataTahunLaluArr = labels.map(label => dataTahunLalu[label] || 0);

        const datasets = [
            {
                label: `Tahun ${tahunIni}`,
                data: dataTahunIniArr,
                backgroundColor: "rgba(37, 83, 247, 1)",
                borderColor: "rgba(37, 83, 247, 1)",
                borderWidth: 1,
            },
            {
                label: `Tahun ${tahunLalu}`,
                data: dataTahunLaluArr,
                backgroundColor: "rgba(217, 232, 53, 1)",
                borderColor: "rgba(217, 232, 53, 1)",
                borderWidth: 1,
            },
        ];

        chartInstance.current = new Chart(ctx, {
            type: "bar",
            data: { labels, datasets },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "top",
                        labels: { color: 'rgb(176, 175, 153)', font: { size: 12 } },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: "rgb(176, 175, 153)", font: { size: 12 } },
                    },
                    x: {
                        ticks: { color: "rgb(176, 175, 153)", font: { size: 12 } },
                    },
                },
            },
        });

        return () => {
            if (chartInstance.current) chartInstance.current.destroy();
        };
    }, [laporanRl51, laporanRl51Lalu, tahunIni, tahunLalu]);

    return (
        <div className="p-5 flex flex-wrap w-full">
            <div className="w-full">
                <div className="max-w-full mx-auto w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <h1 className="uppercase text-center font-bold text-xl">Laporan RL 5.1 - PENGUNJUNG</h1>
                            <canvas ref={chartRef}></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default LaporanRL51;
