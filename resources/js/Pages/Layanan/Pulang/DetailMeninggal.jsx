import React from 'react';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function DetailMeninggal({ detailMeninggal = {} }) {

    const headers = [
        { name: "NO" },
        { name: "COLUMN NAME" },
        { name: "VALUE" },
    ];

    // Generate detailData dynamically from the detail object
    const detailData = Object.keys(detailMeninggal).map((key) => ({
        uraian: key,
        value: detailMeninggal[key],
    }));

    const filteredDetailData = detailData.filter((item) => {
        return item.value !== null && item.value !== undefined && item.value !== "" &&
            (item.value !== 0 || item.uraian === "STATUS_KUNJUNGAN" || item.uraian === "STATUS_AKTIFITAS_KUNJUNGAN");
    });

    // Specify how many rows per table
    const rowsPerTable = Math.ceil(filteredDetailData.length / 2);

    // Split the data into groups
    const tables = [];
    for (let i = 0; i < filteredDetailData.length; i += rowsPerTable) {
        tables.push(filteredDetailData.slice(i, i + rowsPerTable));
    }

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DATA DETAIL PASIEN MENINGGAL
                            </h1>

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
                                                        <TableHeaderCell key={index} className={`${index === 0 ? "w-[5%]" : index === 1 ? "w-[35%]" : "w-[auto]"} 
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
                                                {tableData.map((detailItem, index) => (
                                                    <TableRow key={index}>
                                                        <TableCell>{index + 1 + tableIndex * rowsPerTable}</TableCell>
                                                        <TableCell>{detailItem.uraian}</TableCell>
                                                        <TableCell className="text-wrap">
                                                            {detailItem.uraian === "STATUS" ? (
                                                                detailItem.value === 1 ? "Belum Final" :
                                                                    detailItem.value === 2 ? "Sudah Final" :
                                                                        detailItem.value
                                                            ) : detailItem.uraian === "JENIS_LAHIR_MATI" ? (
                                                                detailItem.value === 1 ? "Iya" :
                                                                    detailItem.value === 2 ? "Tidak" :
                                                                        ''
                                                            ) : detailItem.uraian === "PERISTIWA_PERSALINAN" ? (
                                                                detailItem.value === 0 ? "Tidak" :
                                                                    detailItem.value === 1 ? "Ya" :
                                                                        ''
                                                            ) : detailItem.uraian === "PERISTIWA_KEHAMILAN" ? (
                                                                detailItem.value === 0 ? "Tidak" :
                                                                    detailItem.value === 1 ? "Ya" :
                                                                        ''
                                                            ) : detailItem.uraian === "DILAKUKAN_OPERASI" ? (
                                                                detailItem.value === 0 ? "Tidak" :
                                                                    detailItem.value === 1 ? "Ya" :
                                                                        ''
                                                            ) : detailItem.uraian === "STATUS_VERIFIKASI" ? (
                                                                detailItem.value === 0 ? "Tidak Diverifikasi" :
                                                                    detailItem.value === 1 ? "Menunggu" :
                                                                        detailItem.value === 2 ? "Terverifikasi" :
                                                                            ''
                                                            ) : detailItem.value}
                                                        </TableCell>
                                                    </TableRow>
                                                ))}
                                            </tbody>
                                        </Table>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
