import React, { useState } from 'react';
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function RujukanMingguanTable({ rujukanMingguan }) {
    const [kunjunganMingguanLinks, setRujukanMingguanLinks] = useState(rujukanMingguan.linksRujukanMingguan);

    const handleRujukanMingguanChange = (newLinks) => {
        setRujukanMingguanLinks(newLinks);
    };

    return (
        <div className="max-w-full mx-auto w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                    <h1 className="uppercase text-center font-extrabold text-xl text-indigo-700 dark:text-gray-200 mb-2">
                        Rujukan Mingguan
                    </h1>
                    <Table>
                        <TableHeader>
                            <tr>
                                <TableHeaderCell className='text-nowrap w-[5%]'>TAHUN</TableHeaderCell>
                                <TableHeaderCell className='text-nowrap w-[12%]'>MINGGU KE</TableHeaderCell>
                                <TableHeaderCell className='text-right'>MASUK</TableHeaderCell>
                                <TableHeaderCell className='text-right'>KELUAR</TableHeaderCell>
                                <TableHeaderCell className='text-right'>BALIK</TableHeaderCell>
                            </tr>
                        </TableHeader>
                        <tbody>
                            {Array.isArray(rujukanMingguan?.dataRujukanMingguan) && rujukanMingguan?.dataRujukanMingguan.length > 0 ? (
                                rujukanMingguan?.dataRujukanMingguan.map((data, index) => (
                                    <TableRow key={data.tanggal} isEven={index % 2 === 0}>
                                        <TableCell>{data.tahun}</TableCell>
                                        <TableCell>{data.minggu}</TableCell>
                                        <TableCell className='text-right'>{data.masuk}</TableCell>
                                        <TableCell className='text-right'>{data.keluar}</TableCell>
                                        <TableCell className='text-right'>{data.balik}</TableCell>
                                    </TableRow>
                                ))
                            ) : (
                                <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                    <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                </tr>
                            )}
                        </tbody>
                    </Table>
                    <Pagination
                        links={kunjunganMingguanLinks}
                        onPageChange={handleRujukanMingguanChange}
                    />
                </div>
            </div>
        </div>
    );
}
