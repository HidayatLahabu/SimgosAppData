import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/Input/TextInput";
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";

export default function OrderLabDetail({
    auth,
    dataTable,
    queryParams = {},
}) {

    const headers = [
        { name: "ORDER ID", className: "w-[10%]" },
        { name: "TANGGAL", className: "w-[10%] text-center" },
        { name: "NORM", className: "w-[7%]" },
        { name: "NAMA PASIEN" },
        { name: "TINDAKAN" },
        { name: "PARAMETER" },
        { name: "HASIL", className: "text-center w-[8%]" },
        { name: "NILAI NORMAL", className: "text-center w-[10%]" },
        { name: "SATUAN", className: "text-center w-[7%]" },
        { name: "STATUS", className: "text-center w-[8%]" },
    ];

    // ===== SEARCH =====
    const searchFieldChanged = (search, value) => {
        const params = { ...queryParams, page: 1 };

        if (value) {
            params[search] = value;
        } else {
            delete params[search];
        }

        router.get(route('layananLab.orderLabDetail'), params, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const onInputChange = (search, e) => {
        searchFieldChanged(search, e.target.value);
    };

    const onKeyPress = (search, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(search, e.target.value);
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Order Detail Laboratorium" />

            <div className="pt-5 pb-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 shadow-sm sm:rounded-lg">
                        <div className="p-5 dark:bg-indigo-950 text-gray-900 dark:text-gray-100">

                            <h1 className="text-center font-bold text-2xl uppercase mb-4">
                                Data Detail Order Laboratorium
                            </h1>

                            <Table>
                                {/* SEARCH */}
                                <TableHeader>
                                    <tr>
                                        <th colSpan={10} className="px-3 py-2">
                                            <TextInput
                                                className="w-full"
                                                defaultValue={queryParams.search || ''}
                                                placeholder="Cari order, norm, nama pasien, atau tindakan"
                                                onChange={e => onInputChange('search', e)}
                                                onKeyPress={e => onKeyPress('search', e)}
                                            />
                                        </th>
                                    </tr>
                                </TableHeader>

                                {/* HEADER */}
                                <TableHeader>
                                    <tr>
                                        {headers.map((header, i) => (
                                            <TableHeaderCell
                                                key={i}
                                                className={header.className || ''}
                                            >
                                                {header.name}
                                            </TableHeaderCell>
                                        ))}
                                    </tr>
                                </TableHeader>

                                {/* BODY */}
                                <tbody>
                                    {dataTable.data.length > 0 ? (
                                        dataTable.data.map((row, index) => (
                                            <TableRow
                                                key={`${row.order_id}-${index}`}
                                                isEven={index % 2 === 0}
                                            >
                                                <TableCell>{row.order_id}</TableCell>
                                                <TableCell className="text-center">
                                                    {row.tanggal_order}
                                                </TableCell>
                                                <TableCell>{row.norm}</TableCell>
                                                <TableCell className="uppercase">
                                                    {row.nama_pasien}
                                                </TableCell>
                                                <TableCell>{row.tindakan}</TableCell>
                                                <TableCell>{row.parameter}</TableCell>
                                                <TableCell className="text-center font-semibold">
                                                    {row.HASIL}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {row.NILAI_NORMAL}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {row.SATUAN}
                                                </TableCell>
                                                <TableCell className="text-center">
                                                    {row.status_hasil === 1
                                                        ? 'Final'
                                                        : 'Belum Final'}
                                                </TableCell>
                                            </TableRow>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="10" className="px-3 py-4 text-center">
                                                Tidak ada data yang ditampilkan
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </Table>

                            <Pagination links={dataTable.links} />

                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
