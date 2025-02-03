import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { router } from "@inertiajs/react";
import TextInput from "@/Components/Input/TextInput";
import Pagination from "@/Components/Pagination";
import Cetak from './Cetak';
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";

export default function PengunjungPerPasien({
    auth,
    dataTable,
    ruangan,
    caraBayar,
    dokter,
    queryParams = {},
}) {

    const headers = [
        { name: "PENDAFTARAN", className: "w-[6%]" },
        { name: "NORM", className: "w-[6%]" },
        { name: "NAMA PASIEN", className: "text-center" },
        { name: "STATUS", className: "text-center w-[4%]" },
        { name: "TANGGAL REGISTRASI", className: "text-wrap text-center w-[10%]" },
        { name: "CARA BAYAR", className: "text-wrap" },
        { name: "UNIT PELAYANAN", className: "text-wrap" },
        { name: "DOKTER", className: "text-wrap" },
    ];

    // Function to handle search input changes
    const searchFieldChanged = (search, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[search] = value;
        } else {
            delete updatedParams[search];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('pengunjungPerPasien.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (search, e) => {
        const value = e.target.value;
        searchFieldChanged(search, value);
    };

    // Function to handle Enter key press in search input
    const onKeyPress = (search, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(search, e.target.value);
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">

                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <h1 className="uppercase text-center font-bold text-2xl text-gray-100 pt-4">
                            LAPORAN PENGUNJUNG PER PASIEN
                        </h1>
                        <p className="text-center text-gray-100 pb-4">
                            <strong>Tanggal: </strong>{new Date().toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                            })}
                        </p>
                        <div className="pl-5 pr-5 pb-5 text-gray-900 dark:text-gray-100 w-full">
                            <div className="overflow-x-auto">

                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={8} className="px-3 py-2">
                                                <div className="flex items-center space-x-2">
                                                    <TextInput
                                                        className="flex-1"
                                                        defaultValue={queryParams.search || ''}
                                                        placeholder="Cari data berdasarkan NORM, nama pasien, status, cara bayar, ruangan atau dokter"
                                                        onChange={e => onInputChange('search', e)}
                                                        onKeyPress={e => onKeyPress('search', e)}
                                                    />
                                                </div>
                                            </th>
                                        </tr>
                                    </TableHeader>
                                    <TableHeader>
                                        <tr className='text-xs'>
                                            {headers.map((header, index) => (
                                                <TableHeaderCell key={index} className={header.className || ""}>
                                                    {header.name}
                                                </TableHeaderCell>
                                            ))}
                                        </tr>
                                    </TableHeader>
                                    <tbody>
                                        {dataTable.data.length > 0 ? (
                                            dataTable.data.map((data, index) => (
                                                <TableRow key={data.NOPEN} isEven={index % 2 === 0}>
                                                    <TableCell>{data.NOPEN}</TableCell>
                                                    <TableCell>{data.NORM}</TableCell>
                                                    <TableCell>{data.NAMA_LENGKAP}</TableCell>
                                                    <TableCell>{data.STATUSPENGUNJUNG}</TableCell>
                                                    <TableCell>{data.TGLTERIMA}</TableCell>
                                                    <TableCell>{data.CARABAYAR}</TableCell>
                                                    <TableCell>{data.UNITPELAYANAN}</TableCell>
                                                    <TableCell>{data.DOKTER_REG}</TableCell>
                                                </TableRow>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="8" className="px-3 py-3 text-center">
                                                    Tidak ada data yang dapat ditampilkan
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </Table>
                                <Pagination links={dataTable.links} />
                            </div>
                        </div>
                    </div>
                </div >
            </div >

            <div className="w-full">
                <Cetak
                    caraBayar={caraBayar}
                    ruangan={ruangan}
                    dokter={dokter}
                />
            </div>

        </AuthenticatedLayout >
    );
}
