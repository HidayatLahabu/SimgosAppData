import React, { useState, useEffect } from 'react';

export default function MonthlyRujukanTable({ rujukanBulanan = [] }) {
    const [items, setItems] = useState(rujukanBulanan);

    useEffect(() => {
        // If data is passed as a prop, use it directly
        if (Array.isArray(rujukanBulanan)) {
            setItems(rujukanBulanan);
        } else {
            console.error('Invalid data passed to component:', rujukanBulanan);
        }
    }, [rujukanBulanan]);

    // Ensure items is always an array
    const sortedItems = Array.isArray(items) ? [...items] : [];

    // Filter items to include only rows where any of the columns MASUK, KELUAR, or BALIK is greater than 0
    const filteredItems = sortedItems.filter(
        (data) =>
            data.MASUK > 0 || data.KELUAR > 0 || data.BALIK > 0
    );

    // Find the maximum value for JUMLAH
    const maxJumlah = filteredItems.length > 0
        ? Math.max(...filteredItems.map((item) => item.JUMLAH))
        : null;

    return (
        <div className="max-w-full mx-auto w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                    <h1 className="uppercase text-center font-extrabold text-xl text-indigo-700 dark:text-yellow-500 mb-2">
                        Rujukan Bulanan
                    </h1>
                    <div className="overflow-x-auto">
                        <table className="min-w-full border-collapse border border-gray-200 dark:border-gray-700">
                            <thead className="bg-indigo-700 dark:bg-indigo-900 border border-gray-300 dark:border-gray-600 uppercase text-yellow-500">
                                <tr>
                                    <th className="border border-gray-100 dark:border-gray-600 px-4 py-2 text-left">Bulan</th>
                                    <th className="border border-gray-100 dark:border-gray-600 px-4 py-2 text-center">Rujukan Masuk</th>
                                    <th className="border border-gray-100 dark:border-gray-600 px-4 py-2 text-center">Rujukan Keluar</th>
                                    <th className="border border-gray-100 dark:border-gray-600 px-4 py-2 text-center">Rujukan Balik</th>
                                </tr>
                            </thead>
                            <tbody>
                                {filteredItems.length > 0 ? (
                                    filteredItems.map((data, index) => (
                                        <tr
                                            key={index}
                                            className={`hover:bg-indigo-100 dark:hover:bg-indigo-800 ${index % 2 === 0
                                                ? 'bg-gray-50 dark:bg-indigo-950'
                                                : 'bg-gray-50 dark:bg-indigo-950'
                                                }`}
                                        >
                                            <td
                                                className={`border border-gray-600 px-4 py-2 ${data.JUMLAH === maxJumlah ? 'text-green-500 font-bold' : ''
                                                    }`}
                                            >
                                                {data.BULAN}
                                            </td>
                                            <td
                                                className={`border border-gray-600 px-4 py-2 text-center ${data.MASUK === maxJumlah ? 'text-green-500 font-bold' : ''
                                                    }`}
                                            >
                                                {data.MASUK.toLocaleString()}
                                            </td>
                                            <td
                                                className={`border border-gray-600 px-4 py-2 text-center ${data.KELUAR === maxJumlah ? 'text-green-500 font-bold' : ''
                                                    }`}
                                            >
                                                {data.KELUAR.toLocaleString()}
                                            </td>
                                            <td
                                                className={`border border-gray-600 px-4 py-2 text-center ${data.BALIK === maxJumlah ? 'text-green-500 font-bold' : ''
                                                    }`}
                                            >
                                                {data.BALIK.toLocaleString()}
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="4" className="text-center px-4 py-2 text-gray-500">
                                            Tidak ada data.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    );
}
