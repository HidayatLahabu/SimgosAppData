import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

// Register chart elements
Chart.register(...registerables);

const LaporanRL32 = ({ tahunIni, tahunLalu, laporanRl32, laporanRl32Lalu }) => {
    const chartRef = useRef(null);
    let chartInstance = null;

    useEffect(() => {
        if (chartInstance) {
            chartInstance.destroy();
        }

        const ctx = chartRef.current.getContext("2d");

        // Filter data untuk hanya nilai > 100
        const filteredDataTahunIni = laporanRl32.filter(item =>
            (item.rujukan > 100 || item.nonrujukan > 100 || item.dirawat > 100 || item.dirujuk > 100 || item.pulang > 100 || item.meninggal > 100 || item.doa > 100)
        );

        const filteredDataTahunLalu = laporanRl32Lalu.filter(item =>
            (item.rujukan > 100 || item.nonrujukan > 100 || item.dirawat > 100 || item.dirujuk > 100 || item.pulang > 100 || item.meninggal > 100 || item.doa > 100)
        );

        // Labels dari data yang sudah di filter
        const labels = filteredDataTahunIni.map(item => item.jenis_pelayanan);

        // Data tahun ini
        const rujukanCountsTahunIni = filteredDataTahunIni.map(item => item.rujukan);
        const nonrujukanCountsTahunIni = filteredDataTahunIni.map(item => item.nonrujukan);
        const dirawatCountsTahunIni = filteredDataTahunIni.map(item => item.dirawat);
        const dirujukCountsTahunIni = filteredDataTahunIni.map(item => item.dirujuk);
        const pulangCountsTahunIni = filteredDataTahunIni.map(item => item.pulang);
        const meninggalCountsTahunIni = filteredDataTahunIni.map(item => item.meninggal);
        const doaCountsTahunIni = filteredDataTahunIni.map(item => item.doa);

        // Data tahun lalu
        const rujukanCountsTahunLalu = filteredDataTahunLalu.map(item => item.rujukan);
        const nonrujukanCountsTahunLalu = filteredDataTahunLalu.map(item => item.nonrujukan);
        const dirawatCountsTahunLalu = filteredDataTahunLalu.map(item => item.dirawat);
        const dirujukCountsTahunLalu = filteredDataTahunLalu.map(item => item.dirujuk);
        const pulangCountsTahunLalu = filteredDataTahunLalu.map(item => item.pulang);
        const meninggalCountsTahunLalu = filteredDataTahunLalu.map(item => item.meninggal);
        const doaCountsTahunLalu = filteredDataTahunLalu.map(item => item.doa);

        // Filter out datasets with no values > 100
        const filterEmptyData = (data) => data.filter((value) => value > 100);

        // Create datasets for the chart
        const datasets = [
            {
                label: `Rujukan ${tahunIni}`,
                data: filterEmptyData(rujukanCountsTahunIni),
                borderColor: "rgba(21, 73, 194)",
                backgroundColor: "rgba(21, 73, 194)",
                borderWidth: 1
            },
            {
                label: `Non-Rujukan ${tahunIni}`,
                data: filterEmptyData(nonrujukanCountsTahunIni),
                borderColor: "rgba(245, 166, 7)",
                backgroundColor: "rgba(245, 166, 7)",
                borderWidth: 1
            },
            {
                label: `Dirawat ${tahunIni}`,
                data: filterEmptyData(dirawatCountsTahunIni),
                borderColor: "rgba(7, 245, 166)",
                backgroundColor: "rgba(7, 245, 166)",
                borderWidth: 1
            },
            {
                label: `Dirujuk ${tahunIni}`,
                data: filterEmptyData(dirujukCountsTahunIni),
                borderColor: "rgba(255, 99, 132)",
                backgroundColor: "rgba(255, 99, 132)",
                borderWidth: 1
            },
            {
                label: `Pulang ${tahunIni}`,
                data: filterEmptyData(pulangCountsTahunIni),
                borderColor: "rgba(54, 162, 235)",
                backgroundColor: "rgba(54, 162, 235)",
                borderWidth: 1
            },
            {
                label: `Meninggal ${tahunIni}`,
                data: filterEmptyData(meninggalCountsTahunIni),
                borderColor: "rgba(153, 102, 255)",
                backgroundColor: "rgba(153, 102, 255)",
                borderWidth: 1
            },
            {
                label: `DOA ${tahunIni}`,
                data: filterEmptyData(doaCountsTahunIni),
                borderColor: "rgba(255, 159, 64)",
                backgroundColor: "rgba(255, 159, 64)",
                borderWidth: 1
            },

            {
                label: `Rujukan ${tahunLalu}`,
                data: filterEmptyData(rujukanCountsTahunLalu),
                borderColor: "rgba(21, 73, 194, 0.4)",
                backgroundColor: "rgba(21, 73, 194, 0.4)",
                borderWidth: 1
            },
            {
                label: `Non-Rujukan ${tahunLalu}`,
                data: filterEmptyData(nonrujukanCountsTahunLalu),
                borderColor: "rgba(245, 166, 7, 0.4)",
                backgroundColor: "rgba(245, 166, 7, 0.4)",
                borderWidth: 1
            },
            {
                label: `Dirawat ${tahunLalu}`,
                data: filterEmptyData(dirawatCountsTahunLalu),
                borderColor: "rgba(7, 245, 166, 0.4)",
                backgroundColor: "rgba(7, 245, 166, 0.4)",
                borderWidth: 1
            },
            {
                label: `Dirujuk ${tahunLalu}`,
                data: filterEmptyData(dirujukCountsTahunLalu),
                borderColor: "rgba(255, 99, 132, 0.4)",
                backgroundColor: "rgba(255, 99, 132, 0.4)",
                borderWidth: 1
            },
            {
                label: `Pulang ${tahunLalu}`,
                data: filterEmptyData(pulangCountsTahunLalu),
                borderColor: "rgba(54, 162, 235, 0.4)",
                backgroundColor: "rgba(54, 162, 235, 0.4)",
                borderWidth: 1
            },
            {
                label: `Meninggal ${tahunLalu}`,
                data: filterEmptyData(meninggalCountsTahunLalu),
                borderColor: "rgba(153, 102, 255, 0.4)",
                backgroundColor: "rgba(153, 102, 255, 0.4)",
                borderWidth: 1
            },
            {
                label: `DOA ${tahunLalu}`,
                data: filterEmptyData(doaCountsTahunLalu),
                borderColor: "rgba(255, 159, 64, 0.4)",
                backgroundColor: "rgba(255, 159, 64, 0.4)",
                borderWidth: 1
            },
        ].filter(dataset => dataset.data.length > 0); // Only include non-empty datasets

        // Membuat chart
        chartInstance = new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: datasets,
            },
            options: {
                responsive: true,
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
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: "rgb(176, 175, 153)",
                            font: {
                                size: 10,
                            },
                        },
                    },
                    x: {
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
            if (chartInstance) chartInstance.destroy();
        };
    }, [laporanRl32, laporanRl32Lalu]);

    return (
        <div className="p-5 flex flex-wrap w-full">
            <div className="w-full">
                <div className="max-w-full mx-auto w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <h1 className="uppercase text-center font-bold text-xl">Laporan RL 3.2 - RAWAT DARURAT</h1>
                            <canvas ref={chartRef}></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default LaporanRL32;
