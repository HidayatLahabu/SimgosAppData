import React from 'react';
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";

export default function SatuSehatTable({ items = [] }) {

    const headers = [
        { name: "PARAMETER SATUSEHAT" },
        { name: "TOTAL DATA", className: "text-center" },
        { name: "MEMILIKI ID", className: "text-center" },
        { name: "TIDAK MEMILIKI ID", className: "text-center" },
        { name: "PERSENTASE", className: "text-center" },
        { name: "TANGGAL SINKRONISASI", className: "text-center" },
    ];

    // Check if items is defined and is an array
    if (!Array.isArray(items)) {
        console.error('Expected items to be an array but received:', items);
        return <div className="text-red-500 text-center mt-5">Error: Data not available</div>;
    }

    // Sorting the items array by 'NAMA'
    const sortedItems = [...items].sort((a, b) => a.NAMA.localeCompare(b.NAMA));

    return (
        <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                    <h1 className="uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-gray-200 mb-4">
                        Data SatuSehat
                    </h1>
                    <div className="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <tr>
                                    {headers.map((header, index) => (
                                        <TableHeaderCell key={index} className={header.className || ""}>
                                            {header.name}
                                        </TableHeaderCell>
                                    ))}
                                </tr>
                            </TableHeader>
                            <tbody>
                                {sortedItems.map((item, index) => {
                                    return (
                                        <tr
                                            key={index}
                                            className={`hover:bg-indigo-100 dark:hover:bg-indigo-800 ${index % 2 === 0
                                                ? 'bg-gray-50 dark:bg-indigo-950'
                                                : 'bg-gray-50 dark:bg-indigo-950'
                                                }`}
                                        >
                                            <td
                                                className={`border border-gray-600 px-4 py-2 font-medium ${item.PERSEN > 50
                                                    ? 'text-green-500'
                                                    : item.PERSEN == 0
                                                        ? 'text-red-400'
                                                        : 'text-yellow-400'
                                                    }`}
                                            >
                                                {item.NAMA}
                                            </td>
                                            <td
                                                className={`border border-gray-600 px-4 py-2 text-center ${item.PERSEN > 50
                                                    ? 'text-green-500'
                                                    : item.PERSEN == 0
                                                        ? 'text-red-400'
                                                        : 'text-yellow-400'
                                                    }`}
                                            >
                                                {item.TOTAL.toLocaleString()}
                                            </td>
                                            <td
                                                className={`border border-gray-600 px-4 py-2 text-center ${item.PERSEN > 50
                                                    ? 'text-green-500'
                                                    : item.PERSEN == 0
                                                        ? 'text-red-400'
                                                        : 'text-yellow-400'
                                                    }`}
                                            >
                                                {item.MEMILIKI_ID.toLocaleString()}
                                            </td>
                                            <td
                                                className={`border border-gray-600 px-4 py-2 text-center ${item.PERSEN > 50
                                                    ? 'text-green-500'
                                                    : item.PERSEN == 0
                                                        ? 'text-red-400'
                                                        : 'text-yellow-400'
                                                    }`}
                                            >
                                                {item.TIDAK_MEMILIKI_ID.toLocaleString()}
                                            </td>
                                            <td
                                                className={`border border-gray-600 px-4 py-2 text-center ${item.PERSEN > 50
                                                    ? 'text-green-500'
                                                    : item.PERSEN == 0
                                                        ? 'text-red-400'
                                                        : 'text-yellow-400'
                                                    }`}
                                            >
                                                <span>{parseFloat(item.PERSEN).toFixed(2)} %</span>
                                            </td>
                                            <td
                                                className={`border border-gray-600 px-4 py-2 text-center ${item.PERSEN > 50
                                                    ? 'text-green-500'
                                                    : item.PERSEN == 0
                                                        ? 'text-red-400'
                                                        : 'text-yellow-400'
                                                    }`}
                                            >
                                                {item.LAST_UPDATE && !isNaN(new Date(item.LAST_UPDATE))
                                                    ? new Date(item.LAST_UPDATE).toLocaleDateString('id-ID', {
                                                        day: '2-digit',
                                                        month: 'long',
                                                        year: 'numeric',
                                                    })
                                                    : 'Belum Sinkronisasi'}
                                            </td>
                                        </tr>
                                    );
                                })}
                            </tbody>
                        </Table>
                    </div>
                </div>
            </div>
        </div >
    );
}
