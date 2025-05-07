import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

// Register chart elements
Chart.register(...registerables);

const PasienRanap = ({ pasienRanap }) => {
    const chartRef = useRef(null);
    const chartInstanceRef = useRef(null);

    const currentYear = new Date().getFullYear();

    useEffect(() => {
        const ctx = chartRef.current.getContext("2d");

        // Hancurkan chart lama kalau ada
        if (chartInstanceRef.current) {
            chartInstanceRef.current.destroy();
        }

        const filteredData = pasienRanap.filter(item => item.vvip > 0 || item.vip > 0 || item.kls1 > 0 || item.kls2 > 0 || item.kls3 > 0 || item.kls_khusus > 0);

        // Cek jika ada lebih dari satu jenis layanan
        const uniqueLabels = [...new Set(filteredData.map(item => item.jenis_pelayanan))];

        // Jika tidak ada data yang unik, kembalikan
        if (uniqueLabels.length === 0) {
            console.error("Tidak ada data jenis pelayanan yang ditemukan.");
            return;
        }

        // Hitung total kls3 untuk setiap jenis pelayanan
        const totalKls3 = uniqueLabels.map(label => {
            const items = filteredData.filter(item => item.jenis_pelayanan === label);
            return items.reduce((acc, item) => acc + (item.kls3 || 0), 0);
        });

        // Urutkan label berdasarkan data kls3 secara descending
        const sortedLabels = uniqueLabels
            .map((label, index) => ({ label, totalKls3: totalKls3[index] }))
            .sort((a, b) => b.totalKls3 - a.totalKls3)
            .map(item => item.label);

        // Reorder the data based on sortedLabels
        const dataVVIP = sortedLabels.map(label =>
            filteredData.filter(item => item.jenis_pelayanan === label).reduce((acc, item) => acc + (item.vvip || 0), 0)
        );
        const dataVIP = sortedLabels.map(label =>
            filteredData.filter(item => item.jenis_pelayanan === label).reduce((acc, item) => acc + (item.vip || 0), 0)
        );
        const dataKls1 = sortedLabels.map(label =>
            filteredData.filter(item => item.jenis_pelayanan === label).reduce((acc, item) => acc + (item.kls1 || 0), 0)
        );
        const dataKls2 = sortedLabels.map(label =>
            filteredData.filter(item => item.jenis_pelayanan === label).reduce((acc, item) => acc + (item.kls2 || 0), 0)
        );
        const dataKls3 = sortedLabels.map(label =>
            filteredData.filter(item => item.jenis_pelayanan === label).reduce((acc, item) => acc + (item.kls3 || 0), 0)
        );
        const dataKlsKhusus = sortedLabels.map(label =>
            filteredData.filter(item => item.jenis_pelayanan === label).reduce((acc, item) => acc + (item.kls_khusus || 0), 0)
        );

        // Buat chart baru
        chartInstanceRef.current = new Chart(ctx, {
            type: "bar",
            data: {
                labels: sortedLabels,
                datasets: [
                    {
                        label: `VVIP`,
                        data: dataVVIP,
                        backgroundColor: "rgba(255, 99, 132, 0.7)",
                        borderColor: "rgba(255, 99, 132, 1)",
                        borderWidth: 1,
                    },
                    {
                        label: `VIP`,
                        data: dataVIP,
                        backgroundColor: "rgba(54, 162, 235, 1)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 1,
                    },
                    {
                        label: `Kelas I`,
                        data: dataKls1,
                        backgroundColor: "rgba(255, 206, 86, 1)",
                        borderColor: "rgba(255, 206, 86, 1)",
                        borderWidth: 1,
                    },
                    {
                        label: `Kelas II`,
                        data: dataKls2,
                        backgroundColor: "rgba(75, 192, 192, 1)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 1,
                    },
                    {
                        label: `Kelas III`,
                        data: dataKls3,
                        backgroundColor: "rgba(153, 102, 255, 1)",
                        borderColor: "rgba(153, 102, 255, 1)",
                        borderWidth: 1,
                    },
                    {
                        label: `Kelas Khusus`,
                        data: dataKlsKhusus,
                        backgroundColor: "rgba(255, 159, 64, 1)",
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
                                size: 10,
                            },
                        },
                    },
                },
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
    }, [pasienRanap]);

    return (
        <div className="p-5 w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100">
                    <h1 className="uppercase text-center font-bold text-xl">RL 3.1 - PASIEN RAWAT INAP RUANGAN</h1>
                    <canvas ref={chartRef}></canvas>
                </div>
            </div>
        </div>
    );
};

export default PasienRanap;
