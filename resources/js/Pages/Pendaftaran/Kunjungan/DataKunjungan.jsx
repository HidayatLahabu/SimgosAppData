import React from 'react';
import ButtonDetail from "@/Components/ButtonDetail";

export default function DataKunjungan({ nomorPendaftaran, dataKunjungan = {} }) {

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DAFTAR KUNJUNGAN <br />DENGAN NOMOR PENDAFTARAN : {nomorPendaftaran}
                            </h1>
                            <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                    <tr>
                                        <th className="px-3 py-2">NO</th>
                                        <th className="px-3 py-2">NOMOR KUNJUNGAN</th>
                                        <th className="px-3 py-2">NOMOR PENDAFTARAN</th>
                                        <th className="px-3 py-2">TANGGAL MASUK</th>
                                        <th className="px-3 py-2">TANGGAL KELUAR</th>
                                        <th className="px-3 py-2">RUANGAN TUJUAN</th>
                                        <th className="px-3 py-2">MENU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {dataKunjungan.length > 0 ? (
                                        dataKunjungan.map((kunjungan, index) => (
                                            <tr key={index} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td className="px-3 py-3">{index + 1}</td>
                                                <td className="px-3 py-3">{kunjungan.nomor}</td>
                                                <td className="px-3 py-3">{kunjungan.pendaftaran}</td>
                                                <td className="px-3 py-3">{kunjungan.masuk}</td>
                                                <td className="px-3 py-3">{kunjungan.keluar}</td>
                                                <td className="px-3 py-3">{kunjungan.ruangan}</td>
                                                <td className="px-3 py-3">
                                                    {kunjungan.nomor ? (
                                                        <ButtonDetail href={route("kunjungan.tableRme", { id: kunjungan.nomor })} />
                                                    ) : (
                                                        <span className="text-gray-500">No detail available</span>
                                                    )}
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
