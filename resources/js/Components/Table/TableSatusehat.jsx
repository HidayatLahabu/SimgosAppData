import React from "react";
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";

export default function TableSatusehat({ headers, tables, rowsPerTable }) {
    return (
        <div className="flex flex-wrap gap-2">
            {tables.map((tableData, tableIndex) => (
                <div
                    key={tableIndex}
                    className="flex-1 shadow-md rounded-lg"
                >
                    <Table>
                        <TableHeader>
                            <tr>
                                {headers.map((header, index) => (
                                    <TableHeaderCell
                                        key={index}
                                        className={`${index === 0 ? 'w-[10%]' : index === 1 ? 'w-[30%]' : 'w-[60%]'} ${header.className || ""}`}
                                    >
                                        {header.name}
                                    </TableHeaderCell>
                                ))}
                            </tr>
                        </TableHeader>
                        <tbody>
                            {tableData.map((detailItem, index) => (
                                <TableRow key={index}>
                                    <TableCell>{index + 1 + tableIndex * rowsPerTable}</TableCell>
                                    <TableCell>{detailItem.uraian}</TableCell>
                                    <TableCell className="text-wrap">{detailItem.value}</TableCell>
                                </TableRow>
                            ))}
                        </tbody>
                    </Table>
                </div>
            ))}
        </div>
    );
}
