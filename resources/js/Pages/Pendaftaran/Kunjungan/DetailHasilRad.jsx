import React from 'react';
import Table from "@/Components/Table"; // Assuming you have a Table component
import TableHeader from "@/Components/TableHeader"; // Assuming you have TableHeader component
import TableHeaderCell from "@/Components/TableHeaderCell"; // Assuming you have TableHeaderCell component
import TableRow from "@/Components/TableRow"; // Assuming you have TableRow component
import TableCell from "@/Components/TableCell"; // Assuming you have TableCell component

export default function DetailHasilRad({ detailHasilRad = [] }) {

    const headers = [
        { name: "NO", className: "w-[5%]" },
        { name: "COLUMN NAME", className: "w-[10%]" },
        { name: "VALUE", className: "w-[auto]" },
    ];

    const generateDetailData = (hasil) => {
        return [
            { uraian: "HASIL ID", value: hasil.ID },
            { uraian: "TINDAKAN", value: hasil.TINDAKAN },
            { uraian: "TANGGAL", value: hasil.TANGGAL },
            { uraian: "KLINIS", value: hasil.KLINIS },
            { uraian: "KESAN", value: hasil.KESAN },
            { uraian: "USUL", value: hasil.USUL },
            { uraian: "HASIL", value: hasil.HASIL },
            { uraian: "BTK", value: hasil.BTK },
            { uraian: "OLEH", value: hasil.PENGGUNA },
            { uraian: "STATUS", value: hasil.STATUS === 2 ? "Sudah Final" : "Belum Final" }
        ];
    };

    const filteredDetailData = detailHasilRad.flatMap((hasil) => generateDetailData(hasil)).filter(item => item.value !== null && item.value !== undefined && item.value !== "");

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DATA DETAIL HASIL LAYANAN RADIOLOGI
                            </h1>

                            {filteredDetailData.length > 0 ? (
                                <Table>
                                    <TableHeader>
                                        <tr>
                                            {headers.map((header, index) => (
                                                <TableHeaderCell
                                                    key={index}
                                                    className={`${index === 0 ? 'w-[5%]' : index === 1 ? 'w-[20%]' : 'w-[60%]'} ${header.className || ""}`}
                                                >
                                                    {header.name}
                                                </TableHeaderCell>
                                            ))}
                                        </tr>
                                    </TableHeader>
                                    <tbody>
                                        {filteredDetailData.map((detailItem, index) => (
                                            <TableRow key={index}>
                                                <TableCell>{index + 1}</TableCell>
                                                <TableCell>{detailItem.uraian}</TableCell>
                                                <TableCell>{detailItem.value}</TableCell>
                                            </TableRow>
                                        ))}
                                    </tbody>
                                </Table>
                            ) : (
                                <p className="text-center px-3 py-3">Belum ada data</p>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
