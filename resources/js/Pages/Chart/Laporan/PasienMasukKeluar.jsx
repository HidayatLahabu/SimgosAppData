import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

// Register chart elements
Chart.register(...registerables);

const PasienMasukKeluar = ({ pasienMasukKeluar }) => {
    const chartRef = useRef(null);
    const chartInstanceRef = useRef(null);

    const currentYear = new Date().getFullYear();

    useEffect(() => {
        const ctx = chartRef.current.getContext("2d");

        // Hancurkan chart lama kalau ada
        if (chartInstanceRef.current) {
            chartInstanceRef.current.destroy();
        }

        // Filter data yang relevan (data dengan nilai lebih dari 0)
        const filteredData = pasienMasukKeluar.filter(item => item.awal > 0 || item.masuk > 0 || item.keluar > 0 || item.mati > 0);

        // Cek jika ada lebih dari satu jenis layanan
        const uniqueLabels = [...new Set(filteredData.map(item => item.jenis_pelayanan))];

        // Jika tidak ada data yang unik, kembalikan
        if (uniqueLabels.length === 0) {
            console.error("Tidak ada data jenis pelayanan yang ditemukan.");
            return;
        }

        // Calculate the total number of patients that entered for each service
        const totalMasuk = uniqueLabels.map(label =>
            filteredData.filter(item => item.jenis_pelayanan === label).reduce((acc, item) => acc + (item.masuk || 0), 0)
        );

        // Sort the uniqueLabels based on totalMasuk in descending order
        const sortedLabels = uniqueLabels
            .map((label, index) => ({ label, totalMasuk: totalMasuk[index] }))
            .sort((a, b) => b.totalMasuk - a.totalMasuk)
            .map(item => item.label);

        // Reorder the data based on sortedLabels
        const dataAwal = sortedLabels.map(label =>
            filteredData.filter(item => item.jenis_pelayanan === label).reduce((acc, item) => acc + (item.awal || 0), 0)
        );
        const dataMasuk = sortedLabels.map(label =>
            filteredData.filter(item => item.jenis_pelayanan === label).reduce((acc, item) => acc + (item.masuk || 0), 0)
        );
        const dataKeluar = sortedLabels.map(label =>
            filteredData.filter(item => item.jenis_pelayanan === label).reduce((acc, item) => acc + (item.keluar || 0), 0)
        );
        const dataMati = sortedLabels.map(label =>
            filteredData.filter(item => item.jenis_pelayanan === label).reduce((acc, item) => acc + (item.mati || 0), 0)
        );

        // Buat chart baru
        chartInstanceRef.current = new Chart(ctx, {
            type: "bar",
            data: {
                labels: sortedLabels,
                datasets: [
                    {
                        label: `Awal`,
                        data: dataAwal,
                        backgroundColor: "rgba(75, 192, 192)",
                        borderColor: "rgba(75, 192, 192)",
                        borderWidth: 1,
                    },
                    {
                        label: `Masuk`,
                        data: dataMasuk,
                        backgroundColor: "rgba(54, 162, 235",
                        borderColor: "rgba(54, 162, 235)",
                        borderWidth: 1,
                    },
                    {
                        label: `Keluar`,
                        data: dataKeluar,
                        backgroundColor: "rgba(255, 99, 132)",
                        borderColor: "rgba(255, 99, 132)",
                        borderWidth: 1,
                    },
                    {
                        label: `Mati`,
                        data: dataMati,
                        backgroundColor: "rgba(153, 102, 255)",
                        borderColor: "rgba(153, 102, 255)",
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
                            font: {
                                size: 10,
                            },
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
    }, [pasienMasukKeluar]);

    return (
        <div className="p-5 w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100">
                    <h1 className="uppercase text-center font-bold text-xl">RL 31 - PASIEN AWAL, MASUK & KELUAR</h1>
                    <canvas ref={chartRef}></canvas>
                </div>
            </div>
        </div>
    );
};

export default PasienMasukKeluar;
