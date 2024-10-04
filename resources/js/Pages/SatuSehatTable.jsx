import React from 'react';

export default function SatuSehatTable({ items = [] }) {
    // Debugging: log items to see what data is being received
    console.log('Received items:', items);

    // Check if items is defined and is an array
    if (!Array.isArray(items)) {
        console.error('Expected items to be an array but received:', items);
        return <div>Error: Data not available</div>;
    }

    return (
        <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                    <h1 className="uppercase text-center font-bold text-2xl pb-2">DATA SATU SEHAT</h1>
                    <div className="overflow-x-auto">
                        <table className="min-w-full table-auto w-full border">
                            <thead>
                                <tr className='uppercase dark:bg-indigo-700'>
                                    <th className="border px-4 py-2">Nama</th>
                                    <th className="border px-4 py-2">Total</th>
                                    <th className="border px-4 py-2">Memiliki ID</th>
                                    <th className="border px-4 py-2">Tidak Memiliki ID</th>
                                    <th className="border px-4 py-2">Persentase</th>
                                    <th className="border px-4 py-2">Tanggal Sinkronisasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {items.map((item, index) => (
                                    <tr key={index}>
                                        <td className="border px-4 py-2">{item.NAMA}</td>
                                        <td className="border px-4 py-2 text-center">{item.TOTAL}</td>
                                        <td className="border px-4 py-2 text-center">{item.MEMILIKI_ID}</td>
                                        <td className="border px-4 py-2 text-center">{item.TIDAK_MEMILIKI_ID}</td>
                                        <td className="border px-4 py-2 text-center">{parseFloat(item.PERSEN).toFixed(2)} %</td>
                                        <td className="border px-4 py-2 text-center">{item.LAST_UPDATE}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    );
}
