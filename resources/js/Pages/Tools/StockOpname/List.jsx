import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import Pagination from "@/Components/Pagination";
import CreateButton from '@/Components/Button/ButtonCreate';
import TextInput from "@/Components/Input/TextInput";
import TableCellMenu from "@/Components/Table/TableCellMenu";
import ButtonDetail from "@/Components/Button/ButtonDetail";

export default function Index({ auth, stockDetail, queryParams = {} }) {

    // ============================
    // Search handlers
    // ============================
    const searchFieldChanged = (field, value) => {
        const params = { ...queryParams, page: 1 };

        if (value) {
            params[field] = value;
        } else {
            delete params[field];
        }

        router.get(
            route('toolsSO.list', stockDetail.data[0].idSo),
            params,
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    };

    const onInputChange = (field, e) => {
        searchFieldChanged(field, e.target.value);
    };

    const onKeyPress = (field, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(field, e.target.value);
    };

    return (
        <AuthenticatedLayout user={auth.user}>

            <Head title="Tools Stok Opname" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">

                                <h1 className="uppercase text-center font-bold text-xl pb-2">
                                    DATA STOCK OPNAME DETIL
                                </h1>

                                {/* Tombol Tambah */}
                                {stockDetail.data.length > 0 && (
                                    <div className="flex justify-end mb-3">
                                        <CreateButton
                                            href={route('toolsSO.tambah', stockDetail.data[0].idSo)}
                                            text="Tambah Barang"
                                        />
                                    </div>
                                )}

                                <table className="w-full text-sm text-left text-gray-500 dark:text-gray-200 dark:bg-indigo-900">

                                    {/* SEARCH */}
                                    <thead>
                                        <tr>
                                            <th colSpan="12" className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    placeholder="Cari nama barang"
                                                    defaultValue={queryParams.namaBarang || ''}
                                                    onChange={e => onInputChange('namaBarang', e)}
                                                    onKeyPress={e => onKeyPress('namaBarang', e)}
                                                />
                                            </th>
                                        </tr>
                                    </thead>

                                    {/* HEADER */}
                                    <thead className="text-sm font-bold text-gray-700 bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">ID SOD</th>
                                            <th className="px-3 py-2">ID SO</th>
                                            <th className="px-3 py-2">RUANGAN</th>
                                            <th className="px-3 py-2">ID BARANG</th>
                                            <th className="px-3 py-2">NAMA BARANG</th>
                                            <th className="px-3 py-2">SATUAN</th>
                                            <th className="px-3 py-2 text-right">AWAL</th>
                                            <th className="px-3 py-2 text-right">MANUAL</th>
                                            <th className="px-3 py-2 text-right">MASUK</th>
                                            <th className="px-3 py-2 text-right">KELUAR</th>
                                            <th className="px-3 py-2 text-right">SISTEM</th>
                                            <th className="px-3 py-2">MENU</th>
                                        </tr>
                                    </thead>

                                    {/* BODY */}
                                    <tbody>
                                        {stockDetail.data.length > 0 ? (
                                            stockDetail.data.map((row, index) => (
                                                <tr
                                                    key={row.idSod || index}
                                                    className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500"
                                                >
                                                    <td className="px-3 py-3">{row.idSod}</td>
                                                    <td className="px-3 py-3">{row.idSo}</td>
                                                    <td className="px-3 py-3 uppercase">{row.ruangan}</td>
                                                    <td className="px-3 py-3">{row.idBarang}</td>
                                                    <td className="px-3 py-3">{row.nama}</td>
                                                    <td className="px-3 py-3">{row.satuan}</td>
                                                    <td className="px-3 py-3 text-right">{row.awal}</td>
                                                    <td className="px-3 py-3 text-right">{row.manual}</td>
                                                    <td className="px-3 py-3 text-right">{row.masuk}</td>
                                                    <td className="px-3 py-3 text-right">{row.keluar}</td>
                                                    <td className="px-3 py-3 text-right">{row.sistem}</td>
                                                    <TableCellMenu>
                                                        <ButtonDetail
                                                            href={route("toolsSO.edit", { id: row.idSod })}
                                                        />
                                                    </TableCellMenu>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="12" className="px-3 py-3 text-center">
                                                    Tidak ada data yang dapat ditampilkan
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>

                                {/* PAGINATION */}
                                <Pagination links={stockDetail.links} />

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
