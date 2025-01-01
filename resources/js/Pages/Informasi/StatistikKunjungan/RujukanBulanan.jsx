import React, { useState, useEffect } from 'react';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

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
                    <h1 className="uppercase text-center font-extrabold text-xl text-indigo-700 dark:text-gray-200 mb-2">
                        Rujukan Bulanan
                    </h1>
                    <div className="overflow-x-auto">

                        <Table>
                            <TableHeader>
                                <tr>
                                    <TableHeaderCell className='text-nowrap w-[20%]'>BULAN</TableHeaderCell>
                                    <TableHeaderCell className='text-right'>RUJUKAN MASUK</TableHeaderCell>
                                    <TableHeaderCell className='text-right'>RUJUKAN KELUAR</TableHeaderCell>
                                    <TableHeaderCell className='text-right'>RUJUKAN BALIK</TableHeaderCell>
                                </tr>
                            </TableHeader>
                            <tbody>
                                {filteredItems.length > 0 ? (
                                    filteredItems.map((data, index) => (
                                        <TableRow key={data.BULAN} isEven={index % 2 === 0}>
                                            <TableCell>{data.BULAN}</TableCell>
                                            <TableCell className='text-right'>{data.MASUK.toLocaleString()}</TableCell>
                                            <TableCell className='text-right'>{data.KELUAR.toLocaleString()}</TableCell>
                                            <TableCell className='text-right'>{data.BALIK.toLocaleString()}</TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                        <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                    </tr>
                                )}
                            </tbody>
                        </Table>
                    </div>
                </div>
            </div>
        </div>
    );
}
