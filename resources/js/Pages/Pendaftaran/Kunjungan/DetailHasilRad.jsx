import React from 'react';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function DetailHasilRad({ detailHasilRad = {} }) {

    const headers = [
        { name: "NO", className: "w-[5%]" },
        { name: "COLUMN NAME", className: "w-[20%]" },
        { name: "VALUE", className: "w-[auto]" },
    ];

    // Generate detailData dynamically from the detail object
    const detailData = Object.keys(detailHasilRad).map((key) => ({
        uraian: key,
        value: detailHasilRad[key],
    }));

    // Filter out any entries that have a null, undefined, or empty value
    const filteredDetailData = detailData.filter(item => item.value);

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DATA DETAIL HASIL LAYANAN RADIOLOGI
                            </h1>
                            <div className="flex flex-wrap gap-2">
                                <div className="flex-1 shadow-md rounded-lg">
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
                                            {filteredDetailData.map((detailItem, index) => (
                                                <TableRow key={index}>
                                                    <TableCell>{index + 1}</TableCell>
                                                    <TableCell>{detailItem.uraian}</TableCell>
                                                    <TableCell className="text-wrap">
                                                        {detailItem.uraian === "STATUS" ? (
                                                            detailItem.value === 1 ? "Belum Final" :
                                                                detailItem.value === 2 ? "Sudah Final" :
                                                                    detailItem.value
                                                        ) : detailItem.value}
                                                    </TableCell>
                                                </TableRow>
                                            ))}
                                        </tbody>
                                    </Table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
