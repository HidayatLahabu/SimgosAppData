import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/Button/ButtonBack';
import DetailMeninggal from './DetailMeninggal';
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";

export default function Detail({ auth, detail, detailMeninggal }) {

    const headers = [
        { name: "NO" },
        { name: "COLUMN NAME" },
        { name: "VALUE" },
    ];

    function decodeHtml(html) {
        const parser = new DOMParser();
        return parser.parseFromString(html, "text/html").documentElement.textContent;
    }

    // Generate detailData dynamically from the detail object
    const detailData = Object.keys(detail).map((key) => ({
        uraian: key,
        value: detail[key],
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
        <AuthenticatedLayout user={auth.user}>
            <Head title="Layanan" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="relative flex items-center justify-between pb-2">
                                    <ButtonBack href={route("layananPulang.index")} />
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">DATA DETAIL LAYANAN PASIEN PULANG</h1>
                                </div>

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
                                                                    detailItem.value === 1
                                                                        ? "Belum Final"
                                                                        : detailItem.value === 2
                                                                            ? "Sudah Final"
                                                                            : <span dangerouslySetInnerHTML={{ __html: detailItem.value }} />
                                                                ) : (
                                                                    <span dangerouslySetInnerHTML={{ __html: detailItem.value }} />
                                                                )}
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
            {detailMeninggal && (
                <DetailMeninggal detailMeninggal={detailMeninggal} />
            )}

        </AuthenticatedLayout>
    );
}
