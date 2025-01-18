import React from "react";
import { Bar } from "react-chartjs-2";
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
} from "chart.js";

// Register the required chart components
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const CardKunjungan = ({ title, todayValue, date, yesterdayValue, chartData, barColor, titleColor }) => {
    // Calculate the difference between today's and yesterday's values
    const difference = todayValue - yesterdayValue;

    // Define chart options
    const options = {
        responsive: true,
        plugins: {
            legend: {
                display: false,
            },
            tooltip: {
                callbacks: {
                    label: (context) => `${context.dataset.label}: ${context.raw}`,
                },
            },
        },
        scales: {
            x: {
                grid: {
                    display: false,
                },
                ticks: {
                    font: {
                        size: 10,
                    },
                    color: 'rgb(176, 175, 153)',
                },
            },
            y: {
                grid: {
                    display: true,
                },
                ticks: {
                    font: {
                        size: 10,
                    },
                    precision: 0,
                    color: 'rgb(176, 175, 153)',
                },
            },
        },
    };

    // Prepare chart data
    const data = {
        labels: chartData.labels,
        datasets: [
            {
                label: title,
                data: chartData.values,
                backgroundColor: barColor || "rgba(75, 192, 192, 0.6)",
                borderColor: barColor || "rgba(75, 192, 192, 1)",
                borderWidth: 1,
            },
        ],
    };

    return (
        <div className="bg-white bg-gradient-to-r from-indigo-800 to-indigo-900 text-center rounded-lg shadow-lg p-4">
            <div className="flex items-center justify-between mb-2">
                {/* Title with customizable color */}
                <h2
                    className="text-xl font-bold"
                    style={{ color: titleColor || "inherit" }}
                >
                    {title}
                </h2>
                <p className="text-sm text-gray-500 dark:text-gray-400">
                    {date ? `Tanggal Update : ${new Date(date).toLocaleDateString()}` : "Belum Diupdate"}
                </p>
            </div>

            <div className="mt-4">
                <Bar data={data} options={options} />
            </div>
        </div>
    );
};

export default CardKunjungan;
