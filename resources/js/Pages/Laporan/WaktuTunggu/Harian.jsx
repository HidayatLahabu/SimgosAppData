import React from 'react';
import { router } from "@inertiajs/react";
import TextInput from "@/Components/Input/TextInput";
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";

export default function Harian({ dataTable, queryParams = {} }) {

    const headers = [
        { name: "NORM", className: "w-[7%]" },
        { name: "NAMA PASIEN" },
        { name: "PENDAFTARAN", className: "w-[9%]" },
        { name: "RUANGAN" },
        { name: "DPJP" },
        { name: "TANGGAL REGISTRASI", className: "w-[12%]" },
        { name: "TANGGAL DITERIMA", className: "w-[12%" },
        { name: "WAKTU TUNGGU", className: "w-[9%" },
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
        router.get(route('pengunjungWaktuTunggu.index'), updatedParams, {
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

    function convertTimeToMinutes(seconds) {
        return Math.floor(seconds / 60); // Mengubah detik menjadi menit
    }

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-2xl pb-2">WAKTU TUNGGU RAWAT JALAN</h1>
                            <Table>
                                <TableHeader>
                                    <tr>
                                        <th colSpan={8} className="px-3 py-2">
                                            <div className="flex items-center space-x-2">
                                                <TextInput
                                                    className="flex-1"
                                                    defaultValue={queryParams.search || ''}
                                                    placeholder="Cari data berdasarkan NORM, nama pasien, nomor pendaftaran, ruangan atau DPJP"
                                                    onChange={e => onInputChange('search', e)}
                                                    onKeyPress={e => onKeyPress('search', e)}
                                                />
                                            </div>
                                        </th>
                                    </tr>
                                </TableHeader>
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
                                    {dataTable.data.length > 0 ? (
                                        dataTable.data.map((data, index) => (
                                            <TableRow
                                                key={data.NOMOR}
                                                className={`${convertTimeToMinutes(data.SELISIH_DETIK) > 60 ? 'text-red-400' : ''
                                                    }`}
                                            >
                                                <TableCell>{data.NORM}</TableCell>
                                                <TableCell>{data.NAMALENGKAP}</TableCell>
                                                <TableCell>{data.NOPEN}</TableCell>
                                                <TableCell>{data.UNITPELAYANAN}</TableCell>
                                                <TableCell>{data.DOKTER_REG}</TableCell>
                                                <TableCell>{data.TGLREG}</TableCell>
                                                <TableCell>{data.TGLTERIMA}</TableCell>
                                                <TableCell>{data.SELISIH}</TableCell>
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
            </div>
        </div>
    );
}
