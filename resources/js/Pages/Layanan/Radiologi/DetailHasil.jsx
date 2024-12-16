import React from 'react';

export default function DetailHasil({ detailHasil = {} }) {

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DATA DETAIL HASIL LAYANAN RADIOLOGI
                            </h1>
                            <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                    <tr>
                                        <th className="px-3 py-2">NO</th>
                                        <th className="px-3 py-2">HASIL ID</th>
                                        <th className="px-3 py-2">TINDAKAN</th>
                                        <th className="px-3 py-2">TANGGAL</th>
                                        <th className="px-3 py-2">KLINIS</th>
                                        <th className="px-3 py-2">KESAN</th>
                                        <th className="px-3 py-2">USUL</th>
                                        <th className="px-3 py-2">HASIL</th>
                                        <th className="px-3 py-2">BTK</th>
                                        <th className="px-3 py-2">OLEH</th>
                                        <th className="px-3 py-2">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {detailHasil.length > 0 ? (
                                        detailHasil.map((hasil, index) => (
                                            <tr key={index} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td className="px-3 py-3 w-16">{index + 1}</td>
                                                <td className="px-3 py-3 w-56">{hasil.HASIL_ID}</td>
                                                <td className="px-3 py-3 w-56">{hasil.TINDAKAN}</td>
                                                <td className="px-3 py-3 w-56">{hasil.TANGGAL}</td>
                                                <td className="px-3 py-3 w-56">{hasil.KLINIS}</td>
                                                <td className="px-3 py-3 w-56">{hasil.KESAN}</td>
                                                <td className="px-3 py-3 w-56">{hasil.USUL}</td>
                                                <td className="px-3 py-3 w-56">{hasil.HASIL}</td>
                                                <td className="px-3 py-3 w-56">{hasil.BTK}</td>
                                                <td className="px-3 py-3 w-56">{hasil.PENGGUNA}</td>
                                                <td className="px-3 py-3 w-56">
                                                    {hasil.STATUS === 2 ? "Sudah Final" : 'Belum Final'}
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="9" className="text-center px-3 py-3">
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
