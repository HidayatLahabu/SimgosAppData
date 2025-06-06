import React, { useState, useEffect } from 'react';

export default function MonthlyMutasiTable({ mutasiBulanan = [] }) {
    const [items, setItems] = useState(mutasiBulanan);

    useEffect(() => {
        // If data is passed as a prop, use it directly
        if (Array.isArray(mutasiBulanan)) {
            setItems(mutasiBulanan);
        } else {
            console.error('Invalid data passed to component:', mutasiBulanan);
        }
    }, [mutasiBulanan]);  // This will run when the prop `mutasiBulanan` changes

    // Ensure items is always an array
    const sortedItems = Array.isArray(items) ? [...items] : [];

    // Find the maximum value for JUMLAH
    const maxJumlah = sortedItems.length > 0
        ? Math.max(...sortedItems.map((item) => item.JUMLAH))
        : null;

    return (
        <div className="max-w-full mx-auto sm:pl-1 sm:pr-5 lg:pl-1 lg:pr-5 w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                    <h1 className="uppercase text-center font-extrabold text-xl text-indigo-700 dark:text-yellow-500 mb-2">
                        Mutasi
                    </h1>
                    <div className="overflow-x-auto">
                        <table className="min-w-full border-collapse border border-gray-200 dark:border-gray-700">
                            <thead className="bg-indigo-700 dark:bg-indigo-900 border border-gray-300 dark:border-gray-600 uppercase text-yellow-500">
                                <tr>
                                    <th className="border border-gray-100 dark:border-gray-600 px-4 py-2 text-left">Bulan</th>
                                    <th className="border border-gray-100 dark:border-gray-600 px-4 py-2 text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                {sortedItems.length > 0 ? (
                                    sortedItems.map((data, index) => (
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
                                                className={`border border-gray-600 px-4 py-2 text-center ${data.JUMLAH === maxJumlah ? 'text-green-500 font-bold' : ''
                                                    }`}
                                            >
                                                {data.JUMLAH.toLocaleString()}
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="2" className="text-center px-4 py-2 text-gray-500">
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
