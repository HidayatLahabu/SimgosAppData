import React from 'react';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function DetailLoinc({ detailLoinc = {} }) {

    const headers = [
        { name: "NO", className: "w-[10%]" },
        { name: "COLUMN NAME", className: "w-[30%]" },
        { name: "VALUE", className: "w-[60%]" },
    ];

    // Function to map labels with data
    const fieldMappings = [
        { label: 'ID LOINC', key: 'id' },
        { label: 'KATEGORI PEMERIKSAAN', key: 'kategori_pemeriksaan' },
        { label: 'NAMA PEMERIKSAAN', key: 'nama_pemeriksaan' },
        { label: 'PERMINTAAN HASIL', key: 'permintaan_hasil' },
        { label: 'SPESIMEN', key: 'spesimen' },
        { label: 'TIPE HASIL PEMERIKSAAN', key: 'tipe_hasil_pemeriksaan' },
        { label: 'SATUAN', key: 'satuan' },
        { label: 'METODE ANALISIS', key: 'metode_analisis' },
        { label: 'CODE', key: 'code' },
        { label: 'DISPLAY', key: 'display' },
        { label: 'COMPONENT', key: 'component' },
        { label: 'PROPERTY', key: 'property' },
        { label: 'TIMING', key: 'timing' },
        { label: 'SYSTEM', key: 'system' },
        { label: 'SCALE', key: 'scale' },
        { label: 'METHOD', key: 'method' },
        { label: 'UNIT OF MEASURE', key: 'unit_of_measure' },
        { label: 'CODE SYSTEM', key: 'code_system' },
        { label: 'BODY SITE CODE', key: 'body_site_code' },
        { label: 'BODY SITE DISPLAY', key: 'body_site_display' },
        { label: 'BODY SITE CODE SISTEM', key: 'body_site_code_sistem' },
        { label: 'VERSION FIRST RELEASED', key: 'version_first_released' },
        { label: 'VERSION LAST CHANGE', key: 'version_last_change' },
    ];

    // Split fieldMappings into two tables
    const middleIndex = Math.ceil(fieldMappings.length / 2);
    const firstTableData = fieldMappings.slice(0, middleIndex);
    const secondTableData = fieldMappings.slice(middleIndex);

    const renderTable = (data, tableIndex) => (
        <Table>
            <TableHeader>
                <tr>
                    {headers.map((header, index) => (
                        <TableHeaderCell key={`${tableIndex}-${index}`} className={header.className || ""}>
                            {header.name}
                        </TableHeaderCell>
                    ))}
                </tr>
            </TableHeader>
            <tbody>
                {data.map((field, index) => (
                    <TableRow key={`${tableIndex}-${index}`}>
                        <TableCell className="text-xs">{index + 1}</TableCell>
                        <TableCell className="text-xs text-wrap">{field.label}</TableCell>
                        <TableCell className="text-xs text-wrap">{detailLoinc[field.key] || '-'}</TableCell>
                    </TableRow>
                ))}
            </tbody>
        </Table>
    );

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DATA DETAIL LOINC TERMINOLOGI
                            </h1>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    {renderTable(firstTableData, 0)}
                                </div>
                                <div>
                                    {renderTable(secondTableData, 1)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
