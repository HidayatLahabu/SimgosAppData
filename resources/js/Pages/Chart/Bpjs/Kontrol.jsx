import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const Kontrol = ({
    rekonTahunIni, monitoringTahunIni, batalTahunIni,
    rekonTahunLalu, monitoringTahunLalu, batalTahunLalu,
    tahunIni, tahunLalu
}) => {
    const chartRef = useRef(null);
    let chartInstance = null;

    useEffect(() => {
        if (chartInstance) {
            chartInstance.destroy();
        }

        const ctx = chartRef.current.getContext("2d");

        const bulan = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember",
        ];

        const dataRekonTahunIni = Array(12).fill(0);
        const dataMonitoringTahunIni = Array(12).fill(0);
        const dataBatalTahunIni = Array(12).fill(0);

        const dataRekonTahunLalu = Array(12).fill(0);
        const dataMonitoringTahunLalu = Array(12).fill(0);
        const dataBatalTahunLalu = Array(12).fill(0);

        rekonTahunIni.forEach(item => dataRekonTahunIni[item.bulan - 1] = item.total);
        monitoringTahunIni.forEach(item => dataMonitoringTahunIni[item.bulan - 1] = item.total);
        batalTahunIni.forEach(item => dataBatalTahunIni[item.bulan - 1] = item.total);

        rekonTahunLalu.forEach(item => dataRekonTahunLalu[item.bulan - 1] = item.total);
        monitoringTahunLalu.forEach(item => dataMonitoringTahunLalu[item.bulan - 1] = item.total);
        batalTahunLalu.forEach(item => dataBatalTahunLalu[item.bulan - 1] = item.total);

        chartInstance = new Chart(ctx, {
            type: "bar",
            data: {
                labels: bulan,
                datasets: [
                    {
                        label: `Rencana ${tahunIni}`,
                        data: dataRekonTahunIni,
                        backgroundColor: "rgba(54, 162, 235, 0.7)",
                        borderColor: "rgba(54, 162, 235)",
                        borderWidth: 1,
                        stack: "tahunIni",
                    },
                    {
                        label: `Berkunjung ${tahunIni}`,
                        data: dataMonitoringTahunIni,
                        backgroundColor: "rgba(255, 99, 132, 0.7)",
                        borderColor: "rgba(255, 99, 132)",
                        borderWidth: 1,
                        stack: "tahunIni",
                    },
                    {
                        label: `Batal ${tahunIni}`,
                        data: dataBatalTahunIni,
                        backgroundColor: "rgba(255, 206, 86, 0.7)",
                        borderColor: "rgba(255, 206, 86)",
                        borderWidth: 1,
                        stack: "tahunIni",
                    },
                    {
                        label: `Rencana ${tahunLalu}`,
                        data: dataRekonTahunLalu,
                        backgroundColor: "rgba(54, 162, 235, 0.3)",
                        borderColor: "rgba(54, 162, 235, 0.7)",
                        borderWidth: 1,
                        stack: "tahunLalu",
                    },
                    {
                        label: `Berkunjung ${tahunLalu}`,
                        data: dataMonitoringTahunLalu,
                        backgroundColor: "rgba(255, 99, 132, 0.3)",
                        borderColor: "rgba(255, 99, 132, 0.7)",
                        borderWidth: 1,
                        stack: "tahunLalu",
                    },
                    {
                        label: `Batal ${tahunLalu}`,
                        data: dataBatalTahunLalu,
                        backgroundColor: "rgba(255, 206, 86, 0.3)",
                        borderColor: "rgba(255, 206, 86, 0.7)",
                        borderWidth: 1,
                        stack: "tahunLalu",
                    }
                ],
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        grid: { display: true },
                        stacked: true,
                        beginAtZero: true,
                        ticks: { color: "rgb(176, 175, 153)" },
                    },
                    x: {
                        stacked: true,
                        ticks: { color: "rgb(176, 175, 153)" },
                        grid: { display: false },
                    },
                },
                plugins: {
                    legend: {
                        position: "top",
                        labels: { color: "rgb(176, 175, 153)" },
                    },
                },
            }
        });

        return () => {
            if (chartInstance) chartInstance.destroy();
        };
    }, [rekonTahunIni, monitoringTahunIni, batalTahunIni, rekonTahunLalu, monitoringTahunLalu, batalTahunLalu]);

    return (
        <div className="p-5 flex flex-col w-full">
            <div className="w-full">
                <div className="max-w-full mx-auto w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <h1 className="uppercase text-center font-bold text-xl">
                                Perbandingan Data Rencana Kontrol Tahun {tahunIni} dan {tahunLalu}
                            </h1>
                            <div className="h-[400px]">
                                <canvas ref={chartRef}></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Kontrol;
