import React from 'react';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function DetailCatatanLab({ detailCatatanLab }) {

    const headers = [
        { name: "NO" },
        { name: "COLUMN NAME" },
        { name: "VALUE" },
    ];

    // Handle the case where detailCatatan might be null or undefined
    const detailData = detailCatatanLab ? Object.keys(detailCatatanLab).map((key) => ({
        uraian: key,
        value: detailCatatanLab[key],
    })) : [];

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DATA DETAIL CATATAN HASIL KUNJUNGAN LABORATORIUM
                            </h1>

                            <Table>
                                <TableHeader>
                                    <tr>
                                        {headers.map((header, index) => (
                                            <TableHeaderCell key={index} className={`${index === 0 ? "w-[5%]" : index === 1 ? "w-[15%]" : "w-[auto]"} 
                                            ${header.className || ""}`}
                                            >
                                                {
                                                    header.name
                                                }
                                            </TableHeaderCell>
                                        ))}
                                    </tr>
                                </TableHeader>
                                <tbody>
                                    {detailData.length > 0 ? (
                                        detailData.map((item, index) => (
                                            <TableRow key={index}>
                                                <TableCell>{index + 1}</TableCell>
                                                <TableCell>{item.uraian}</TableCell>
                                                <TableCell>{item.uraian === "STATUS" ? (
                                                    item.value === 0 ? "Belum Final" :
                                                        item.value === 1 ? "Sudah Final" :
                                                            item.value
                                                ) : item.value}</TableCell>
                                            </TableRow>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="8" className="text-center px-3 py-3">
                                                No data available
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </Table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
