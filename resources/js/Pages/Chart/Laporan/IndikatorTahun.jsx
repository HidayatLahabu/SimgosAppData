import React, { useEffect, useRef } from "react";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const IndikatorPelayanan = ({ indikatorPelayananTahunan }) => {
    const chartRef = useRef(null);
    const chartInstanceRef = useRef(null);

    // useEffect(() => {
    //     console.log("Data indikatorPelayananTahunan:", indikatorPelayananTahunan);

    //     if (chartInstanceRef.current) {
    //         chartInstanceRef.current.destroy();
    //     }

    //     const ctx = chartRef.current.getContext("2d");

    //     const tahunSekarang = new Date().getFullYear();
    //     const tahunMulai = tahunSekarang - 4;
    //     const tahunRange = Array.from({ length: 5 }, (_, i) => tahunMulai + i);

    //     // Group data berdasarkan tahun dan hitung rata-rata
    //     const grouped = {};
    //     indikatorPelayananTahunan.forEach(item => {
    //         const tahun = item.tahun; // Ambil tahun dari 'YYYY-MM'
    //         if (!tahun) return;

    //         if (!grouped[tahun]) {
    //             grouped[tahun] = {
    //                 bor: 0, avlos: 0, bto: 0, toi: 0, ndr: 0, gdr: 0, count: 0
    //             };
    //         }

    //         grouped[tahun].bor += item.bor || 0;
    //         grouped[tahun].avlos += item.avlos || 0;
    //         grouped[tahun].bto += item.bto || 0;
    //         grouped[tahun].toi += item.toi || 0;
    //         grouped[tahun].ndr += item.ndr || 0;
    //         grouped[tahun].gdr += item.gdr || 0;
    //         grouped[tahun].count += 1;
    //     });

    //     // Hitung rata-rata dan masukkan ke dataMap
    //     const dataMap = {};
    //     Object.entries(grouped).forEach(([tahun, val]) => {
    //         const count = val.count || 1;
    //         dataMap[tahun] = {
    //             bor: +(val.bor / count).toFixed(2),
    //             avlos: +(val.avlos / count).toFixed(2),
    //             bto: +(val.bto / count).toFixed(2),
    //             toi: +(val.toi / count).toFixed(2),
    //             ndr: +(val.ndr / count).toFixed(2),
    //             gdr: +(val.gdr / count).toFixed(2),
    //         };
    //     });

    //     // Siapkan data untuk chart
    //     const labels = [];
    //     const bor = [];
    //     const avlos = [];
    //     const bto = [];
    //     const toi = [];
    //     const ndr = [];
    //     const gdr = [];

    //     tahunRange.forEach(tahun => {
    //         labels.push(tahun.toString());
    //         const data = dataMap[tahun] || {
    //             bor: 0, avlos: 0, bto: 0, toi: 0, ndr: 0, gdr: 0
    //         };
    //         bor.push(data.bor);
    //         avlos.push(data.avlos);
    //         bto.push(data.bto);
    //         toi.push(data.toi);
    //         ndr.push(data.ndr);
    //         gdr.push(data.gdr);
    //     });

    //     chartInstanceRef.current = new Chart(ctx, {
    //         type: "bar",
    //         data: {
    //             labels,
    //             datasets: [
    //                 {
    //                     label: "BOR",
    //                     data: bor,
    //                     backgroundColor: "rgba(54, 162, 235)",
    //                     borderColor: "rgba(54, 162, 235)",
    //                     borderWidth: 1,
    //                 },
    //                 {
    //                     label: "AVLOS",
    //                     data: avlos,
    //                     backgroundColor: "rgba(255, 206, 86)",
    //                     borderColor: "rgba(255, 206, 86)",
    //                     borderWidth: 1,
    //                 },
    //                 {
    //                     label: "BTO",
    //                     data: bto,
    //                     backgroundColor: "rgba(75, 192, 192)",
    //                     borderColor: "rgba(75, 192, 192)",
    //                     borderWidth: 1,
    //                 },
    //                 {
    //                     label: "TOI",
    //                     data: toi,
    //                     backgroundColor: "rgba(153, 102, 255)",
    //                     borderColor: "rgba(153, 102, 255)",
    //                     borderWidth: 1,
    //                 },
    //                 {
    //                     label: "NDR",
    //                     data: ndr,
    //                     backgroundColor: "rgba(255, 99, 132)",
    //                     borderColor: "rgba(255, 99, 132)",
    //                     borderWidth: 1,
    //                 },
    //                 {
    //                     label: "GDR",
    //                     data: gdr,
    //                     backgroundColor: "rgba(255, 159, 64)",
    //                     borderColor: "rgba(255, 159, 64)",
    //                     borderWidth: 1,
    //                 },
    //             ],
    //         },
    //         options: {
    //             responsive: true,
    //             plugins: {
    //                 legend: {
    //                     position: "top",
    //                     labels: {
    //                         color: 'rgb(176, 175, 153)',
    //                         font: {
    //                             size: 10,
    //                         },
    //                     },
    //                 },
    //             },
    //             scales: {
    //                 y: {
    //                     beginAtZero: true,
    //                     ticks: {
    //                         color: 'rgb(176, 175, 153)',
    //                     },
    //                 },
    //                 x: {
    //                     ticks: {
    //                         color: 'rgb(176, 175, 153)',
    //                     },
    //                 },
    //             },
    //         },
    //     });

    //     return () => {
    //         if (chartInstanceRef.current) {
    //             chartInstanceRef.current.destroy();
    //         }
    //     };
    // }, [indikatorPelayananTahunan]);

    useEffect(() => {
        if (chartInstanceRef.current) {
            chartInstanceRef.current.destroy();
        }

        const ctx = chartRef.current.getContext("2d");

        // Ambil semua tahun unik dari data
        const tahunSet = new Set();
        indikatorPelayananTahunan.forEach(item => {
            if (item.tahun) {
                tahunSet.add(item.tahun);
            }
        });

        // Ubah ke array dan urutkan ascending
        let tahunArray = Array.from(tahunSet).sort();

        // Ambil maksimal 5 tahun terakhir
        if (tahunArray.length > 5) {
            tahunArray = tahunArray.slice(tahunArray.length - 5);
        }

        // Group data berdasarkan tahun dan hitung rata-rata
        const grouped = {};
        indikatorPelayananTahunan.forEach(item => {
            const tahun = item.tahun;
            if (!tahun || !tahunArray.includes(tahun)) return; // hanya proses tahun yang dipakai

            if (!grouped[tahun]) {
                grouped[tahun] = {
                    bor: 0, avlos: 0, bto: 0, toi: 0, ndr: 0, gdr: 0, count: 0
                };
            }

            grouped[tahun].bor += item.bor || 0;
            grouped[tahun].avlos += item.avlos || 0;
            grouped[tahun].bto += item.bto || 0;
            grouped[tahun].toi += item.toi || 0;
            grouped[tahun].ndr += item.ndr || 0;
            grouped[tahun].gdr += item.gdr || 0;
            grouped[tahun].count += 1;
        });

        // Hitung rata-rata
        const dataMap = {};
        tahunArray.forEach(tahun => {
            const val = grouped[tahun];
            if (val && val.count > 0) {
                dataMap[tahun] = {
                    bor: +(val.bor / val.count).toFixed(2),
                    avlos: +(val.avlos / val.count).toFixed(2),
                    bto: +(val.bto / val.count).toFixed(2),
                    toi: +(val.toi / val.count).toFixed(2),
                    ndr: +(val.ndr / val.count).toFixed(2),
                    gdr: +(val.gdr / val.count).toFixed(2),
                };
            } else {
                dataMap[tahun] = { bor: 0, avlos: 0, bto: 0, toi: 0, ndr: 0, gdr: 0 };
            }
        });

        // Siapkan data untuk chart berdasarkan tahunArray (yang sudah dipotong 5 tahun terakhir)
        const labels = tahunArray;
        const bor = [];
        const avlos = [];
        const bto = [];
        const toi = [];
        const ndr = [];
        const gdr = [];

        tahunArray.forEach(tahun => {
            const data = dataMap[tahun];
            bor.push(data.bor);
            avlos.push(data.avlos);
            bto.push(data.bto);
            toi.push(data.toi);
            ndr.push(data.ndr);
            gdr.push(data.gdr);
        });

        chartInstanceRef.current = new Chart(ctx, {
            type: "bar",
            data: {
                labels,
                datasets: [
                    { label: "BOR", data: bor, backgroundColor: "rgba(54, 162, 235)", borderColor: "rgba(54, 162, 235)", borderWidth: 1 },
                    { label: "AVLOS", data: avlos, backgroundColor: "rgba(255, 206, 86)", borderColor: "rgba(255, 206, 86)", borderWidth: 1 },
                    { label: "BTO", data: bto, backgroundColor: "rgba(75, 192, 192)", borderColor: "rgba(75, 192, 192)", borderWidth: 1 },
                    { label: "TOI", data: toi, backgroundColor: "rgba(153, 102, 255)", borderColor: "rgba(153, 102, 255)", borderWidth: 1 },
                    { label: "NDR", data: ndr, backgroundColor: "rgba(255, 99, 132)", borderColor: "rgba(255, 99, 132)", borderWidth: 1 },
                    { label: "GDR", data: gdr, backgroundColor: "rgba(255, 159, 64)", borderColor: "rgba(255, 159, 64)", borderWidth: 1 },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: "top", labels: { color: 'rgb(176, 175, 153)', font: { size: 10 } } },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { color: 'rgb(176, 175, 153)' } },
                    x: { ticks: { color: 'rgb(176, 175, 153)' } },
                },
            },
        });

        return () => {
            if (chartInstanceRef.current) {
                chartInstanceRef.current.destroy();
            }
        };
    }, [indikatorPelayananTahunan]);


    return (
        <div className="p-5 flex flex-wrap w-full">
            <div className="w-full">
                <div className="max-w-full mx-auto w-full">
                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                            <h1 className="uppercase text-center font-bold text-xl">Indikator Pelayanan Tahunan</h1>
                            <canvas ref={chartRef}></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default IndikatorPelayanan;
