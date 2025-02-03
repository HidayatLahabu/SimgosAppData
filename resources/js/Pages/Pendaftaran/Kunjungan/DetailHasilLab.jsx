import React from 'react';
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";

export default function DetailHasilLab({ detailHasilLab = {} }) {

    const headers = [
        { name: "NO", className: "w-[5%]" },
        { name: "HASIL ID", className: "w-[7%]" },
        { name: "TINDAKAN" },
        { name: "PARAMETER" },
        { name: "HASIL" },
        { name: "NILAI NORMAL" },
        { name: "SATUAN", className: "w-[10%]" },
        { name: "STATUS", className: "w-[8%]" },
    ];

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DATA DETAIL HASIL KUNJUNGAN LABORATORIUM
                            </h1>

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
                                    {detailHasilLab.length > 0 ? (
                                        detailHasilLab.map((item, index) => (
                                            <TableRow key={index}>
                                                <TableCell>{index + 1}</TableCell>
                                                <TableCell>{item.HASIL_ID}</TableCell>
                                                <TableCell>{item.TINDAKAN}</TableCell>
                                                <TableCell>{item.PARAMETER}</TableCell>
                                                <TableCell>{item.HASIL}</TableCell>
                                                <TableCell>{item.NILAI_NORMAL}</TableCell>
                                                <TableCell>{item.SATUAN}</TableCell>
                                                <TableCell>{item.STATUS === 1 ? "Sudah Final" : 'Belum Final'}</TableCell>
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
