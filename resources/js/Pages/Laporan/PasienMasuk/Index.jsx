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
import { formatDate } from '@/utils/formatDate';

export default function PengunjungPerPasien({
    auth,
    tglAwal,
    tglAkhir,
    dataTable,
    ruangan,
    caraBayar,
    queryParams = {},
}) {

    const headers = [
        { name: "NORM", className: "w-[6%]" },
        { name: "NAMA PASIEN" },
        { name: "STATUS", className: "text-center w-[4%]" },
        { name: "CARA BAYAR", className: "text-wrap" },
        { name: "UNIT PELAYANAN", className: "text-wrap" },
        { name: "NOPEN", className: "text-center w-[7%]" },
        { name: "REGISTRASI", className: "text-center w-[8%]" },
        { name: "MASUK", className: "text-center w-[8%]" },
        { name: "SELISIH", className: "text-center w-[10%]" },
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
        router.get(route('kegiatanPasienMasuk.index'), updatedParams, {
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

    const getRowClass = (selisih) => {
        const match = selisih.match(/(\d+) hari (\d+):(\d+):(\d+)/);
        if (match) {
            const days = parseInt(match[1]) || 0;
            const hours = parseInt(match[2]) || 0;
            const minutes = parseInt(match[3]) || 0;

            // Konversi ke jam
            const totalHours = (days * 24) + hours + (minutes / 60);

            return totalHours > 1 ? 'text-red-500' : '';
        }
        return '';
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Laporan" />

            <div className="py-5 flex flex-wrap w-full">
                <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">

                    <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                        <h1 className="uppercase text-center font-bold text-2xl text-gray-100 pt-4">
                            LAPORAN KEGIATAN PASIEN MASUK
                        </h1>
                        <p className="text-center text-gray-100 pb-4">
                            <strong>Periode Tanggal: </strong>{formatDate(tglAwal)} s.d {formatDate(tglAkhir)}
                        </p>
                        <div className="pl-5 pr-5 pb-5 text-gray-900 dark:text-gray-100 w-full">
                            <div className="overflow-x-auto">

                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={9} className="px-3 py-2">
                                                <div className="flex items-center space-x-2">
                                                    <TextInput
                                                        className="flex-1"
                                                        defaultValue={queryParams.search || ''}
                                                        placeholder="Cari data berdasarkan NORM, nama pasien, status, cara bayar, ruangan"
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
                                                <TableRow
                                                    key={data.NOPEN}
                                                    isEven={index % 2 === 0}
                                                    className={getRowClass(data.SELISIH)}
                                                >
                                                    <TableCell>{data.NORM}</TableCell>
                                                    <TableCell>{data.NAMA_LENGKAP}</TableCell>
                                                    <TableCell className='text-center'>{data.STATUSPENGUNJUNG}</TableCell>
                                                    <TableCell>{data.CARABAYAR}</TableCell>
                                                    <TableCell>{data.UNITPELAYANAN}</TableCell>
                                                    <TableCell>{data.NOPEN}</TableCell>
                                                    <TableCell className='text-center'>{data.TGLREG}</TableCell>
                                                    <TableCell className='text-center'>{data.TGLMASUK}</TableCell>
                                                    <TableCell className='text-center'>{data.SELISIH}</TableCell>
                                                </TableRow>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="9" className="px-3 py-3 text-center">
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
                />
            </div>

        </AuthenticatedLayout >
    );
}
