import React, { useState, useEffect } from 'react';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function MonthlyKunjunganTable({ kunjunganBulanan = [] }) {
    const [items, setItems] = useState(kunjunganBulanan);

    useEffect(() => {
        // If data is passed as a prop, use it directly
        if (Array.isArray(kunjunganBulanan)) {
            setItems(kunjunganBulanan);
        } else {
            console.error('Invalid data passed to component:', kunjunganBulanan);
        }
    }, [kunjunganBulanan]);

    // Ensure items is always an array
    const sortedItems = Array.isArray(items) ? [...items] : [];

    // Filter untuk hanya menampilkan baris dengan nilai > 0
    const filteredItems = sortedItems.filter(
        (data) => data.RANAP > 0 || data.DARURAT > 0 || data.RANAP > 0
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
                        Kunjungan Bulanan
                    </h1>
                    <div className="overflow-x-auto">

                        <Table>
                            <TableHeader>
                                <tr>
                                    <TableHeaderCell className='text-nowrap w-[20%]'>BULAN</TableHeaderCell>
                                    <TableHeaderCell className='text-right'>RAWAT JALAN</TableHeaderCell>
                                    <TableHeaderCell className='text-right'>RAWAT DARURAT</TableHeaderCell>
                                    <TableHeaderCell className='text-right'>RAWAT INAP</TableHeaderCell>
                                </tr>
                            </TableHeader>
                            <tbody>
                                {filteredItems.length > 0 ? (
                                    filteredItems.map((data, index) => (
                                        <TableRow key={data.BULAN} isEven={index % 2 === 0}>
                                            <TableCell>{data.BULAN}</TableCell>
                                            <TableCell className='text-right'>{data.RAJAL.toLocaleString()}</TableCell>
                                            <TableCell className='text-right'>{data.DARURAT.toLocaleString()}</TableCell>
                                            <TableCell className='text-right'>{data.RANAP.toLocaleString()}</TableCell>
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
