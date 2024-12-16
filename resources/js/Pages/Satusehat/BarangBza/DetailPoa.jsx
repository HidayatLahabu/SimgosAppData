import React from 'react';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function DetailPoa({ detailPoa = {} }) {

    const headers = [
        { name: "NO" },
        { name: "COLUMN NAME" },
        { name: "VALUE" },
    ];

    // Function to map labels with data
    const fieldMappings = [
        { label: 'ID POA', key: 'id' },
        { label: 'DISPLAY', key: 'display' },
        { label: 'POV', key: 'pov' },
        { label: 'NAMA DAGANG', key: 'nama_dagang' },
        { label: 'UNIT LOGISTIK TERKECIL', key: 'unit_logistik_terkecil' },
        { label: 'BENTUK SEDIAAN', key: 'bentuk_sediaan' },
        { label: 'ID BENTUK SEDIAAN', key: 'id_bentuk_sediaan' },
        { label: 'GOLONGAN OBAT', key: 'gologan_obat' },
        { label: 'URL', key: 'url' },
        { label: 'STATUS', key: 'status' },
    ];

    return (
        <div className="overflow-auto w-full">
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
                    {Object.keys(detailPoa).length > 0 ? (
                        fieldMappings.map((field, index) => (
                            <TableRow key={index}>
                                <TableCell className="text-xs">{index + 1}</TableCell>
                                <TableCell className="text-xs text-wrap">{field.label}</TableCell>
                                <TableCell className="text-xs text-wrap">{detailPoa[field.key] || '-'}</TableCell>
                            </TableRow>
                        ))
                    ) : (
                        <tr>
                            <td colSpan="2" className="text-center px-3 py-3">
                                No data available
                            </td>
                        </tr>
                    )}
                </tbody>
            </Table>
        </div>
    );
}
