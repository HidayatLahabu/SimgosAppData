import React from 'react';

export default function RekonObatDetail({ judulRme, dataTable = {} }) {

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DATA DETAIL {judulRme}
                            </h1>
                            <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                    <tr>
                                        <th className="px-3 py-2">ID</th>
                                        <th className="px-3 py-2">OBAT DARI LUAR</th>
                                        <th className="px-3 py-2">DOSIS</th>
                                        <th className="px-3 py-2">FREKUENSI</th>
                                        <th className="px-3 py-2">RUTE</th>
                                        <th className="px-3 py-2">TINDAK LANJUT</th>
                                        <th className="px-3 py-2">PERUBAHAN ATURAN PAKAI</th>
                                        <th className="px-3 py-2">JENIS REKONSILIASI</th>
                                        <th className="px-3 py-2">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {dataTable.length > 0 ? (
                                        dataTable.map((item, index) => (
                                            <tr key={index} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td className="px-3 py-3 w-16">{item.id}</td>
                                                <td className="px-3 py-3 w-56">{item.obatDariLuar}</td>
                                                <td className="px-3 py-3 w-56">{item.dosis}</td>
                                                <td className="px-3 py-3 w-56">{item.frekuensi}</td>
                                                <td className="px-3 py-3 w-56">{item.rute}</td>
                                                <td className="px-3 py-3 w-56">{item.tindakLanjut}</td>
                                                <td className="px-3 py-3 w-56">{item.perubahanAturanPakai}</td>
                                                <td className="px-3 py-3 w-56">{item.jenisRekonsiliasi}</td>
                                                <td className="px-3 py-3 w-56">
                                                    {item.status === 1 ? "Sudah Final" : 'Belum Final'}
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="8" className="text-center px-3 py-3">
                                                No data available
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
