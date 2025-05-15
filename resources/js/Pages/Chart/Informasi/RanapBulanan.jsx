import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const RanapBulanan = ({
    ranapBulananIni, ranapBulananLalu, tahunIni, tahunLalu
}) => {
    const chartRef = useRef(null);
    let chartInstance = null;

    useEffect(() => {
        if (chartInstance) {
            chartInstance.destroy();
        }

        const ctx = chartRef.current.getContext("2d");

        // Prepare data arrays for the current and previous years
        const dataMasukTahunIni = Array(12).fill(0);
        const dataDirawatTahunIni = Array(12).fill(0);
        const dataKeluarTahunIni = Array(12).fill(0);

        const dataMasukTahunLalu = Array(12).fill(0);
        const dataDirawatTahunLalu = Array(12).fill(0);
        const dataKeluarTahunLalu = Array(12).fill(0);

        // Populate data from ranapBulananIni and ranapBulananLalu for current and last year
        ranapBulananIni.forEach(item => {
            const bulan = item.bulan - 1; // Convert to 0-based index
            dataMasukTahunIni[bulan] = item.masuk;
            dataDirawatTahunIni[bulan] = item.dirawat;
            dataKeluarTahunIni[bulan] = item.keluar;
        });

        ranapBulananLalu.forEach(item => {
            const bulan = item.bulan - 1; // Convert to 0-based index
            dataMasukTahunLalu[bulan] = item.masuk;
            dataDirawatTahunLalu[bulan] = item.dirawat;
            dataKeluarTahunLalu[bulan] = item.keluar;
        });

        // Chart.js data configuration
        const data = {
            labels: [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember",
            ],
            datasets: [
                {
                    label: `Masuk ${tahunLalu}`,
                    data: dataMasukTahunLalu,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)', // Lighter red
                    stack: 'tahunLalu', // <-- penting!
                },
                {
                    label: `Dirawat ${tahunLalu}`,
                    data: dataDirawatTahunLalu,
                    backgroundColor: 'rgba(49, 180, 245, 0.5)', // Lighter blue
                    stack: 'tahunLalu',
                },
                {
                    label: `Keluar ${tahunLalu}`,
                    data: dataKeluarTahunLalu,
                    backgroundColor: 'rgba(69, 196, 88, 0.5)', // Lighter green
                    stack: 'tahunLalu',
                },
                {
                    label: `Masuk ${tahunIni}`,
                    data: dataMasukTahunIni,
                    backgroundColor: 'rgba(255, 99, 132)', // Darker red
                    stack: 'tahunIni',
                },
                {
                    label: `Dirawat ${tahunIni}`,
                    data: dataDirawatTahunIni,
                    backgroundColor: 'rgba(49, 180, 245)', // Darker blue
                    stack: 'tahunIni',
                },
                {
                    label: `Keluar ${tahunIni}`,
                    data: dataKeluarTahunIni,
                    backgroundColor: 'rgba(69, 196, 88)', // Darker green
                    stack: 'tahunIni',
                },
            ]
        };

        const allData = [
            ...dataMasukTahunIni,
            ...dataDirawatTahunIni,
            ...dataKeluarTahunIni,
            ...dataMasukTahunLalu,
            ...dataDirawatTahunLalu,
            ...dataKeluarTahunLalu
        ];

        const maxYValue = Math.max(...allData) * 1;

        // Chart.js configuration
        const options = {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function (tooltipItem) {
                            return `${tooltipItem.dataset.label}: ${tooltipItem.raw}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    suggestedMax: maxYValue,
                },
            }
        };

        // Create chart
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });

        // Cleanup chart instance on unmount
        return () => {
            if (chartInstance) {
                chartInstance.destroy();
            }
        };
    }, [ranapBulananIni, ranapBulananLalu, tahunIni, tahunLalu]);

    return (
        <div className="p-5 flex flex-col w-full h-full">
            <div className="max-w-full mx-auto w-full">
                <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                    <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                        <h1 className="uppercase text-center font-bold text-xl">
                            Rawat Inap Bulanan Tahun {tahunIni} dan {tahunLalu}
                        </h1>
                        <div>
                            <canvas ref={chartRef}></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default RanapBulanan;
