import React from 'react';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function DetailPov({ detailPov = {} }) {
    const headers = [
        { name: "NO" },
        { name: "COLUMN NAME" },
        { name: "VALUE" },
    ];

    // Function to map labels with data
    const fieldMappings = [
        { label: 'ID POV', key: 'id' },
        { label: 'DISPLAY', key: 'display' },
        { label: 'UNIT OF MESURE', key: 'unit_of_mesure' },
        { label: 'JENIS', key: 'jenis' },
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
                    {Object.keys(detailPov).length > 0 ? (
                        fieldMappings.map((field, index) => (
                            <TableRow key={index}>
                                <TableCell className="text-xs">{index + 1}</TableCell>
                                <TableCell className="text-xs text-wrap">{field.label}</TableCell>
                                <TableCell className="text-xs text-wrap">{detailPov[field.key] || '-'}</TableCell>
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
