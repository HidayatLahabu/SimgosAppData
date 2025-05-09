import React, { useEffect } from 'react';
import { formatNumber } from "@/utils/formatNumber";


export default function PrintBulan({
    statistikBulanan
}) {

    useEffect(() => {
        import('@/../../resources/css/print.css');
    }, []);

    const bulanNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    return (
        <div className="py-3 overflow-auto">
            <h1 className="text-center font-bold">
                Indikator Pelayanan Per Bulan
            </h1>
            <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                <thead className="uppercase font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                    <tr>
                        <th className="px-3 py-2 border border-gray-500 border-solid">Bulan</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[15%]">Bed Occupancy Rate</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[15%]">Average Length of Stay</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[15%]">Bed Turn Over</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[15%]">Turn Over Interval</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[15%]">Net Death Rate</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[15%]">Gross Death Rate</th>
                    </tr>
                </thead>

                <tbody>
                    {statistikBulanan && statistikBulanan.data && statistikBulanan.data.length > 0 ? (
                        statistikBulanan.data.map((data, index) => (
                            <tr key={data.BULAN} className="border-b bg-white dark:border-gray-500">
                                <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                    {bulanNames[data.BULAN - 1]}
                                </td>
                                <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                    {data.BOR}
                                </td>
                                <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                    {data.AVLOS}
                                </td>
                                <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                    {formatNumber(data.BTO)}
                                </td>
                                <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                    {data.TOI}
                                </td>
                                <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                    {formatNumber(data.NDR)}
                                </td>
                                <td className="px-3 py-2 text-center border border-gray-500 border-solid">
                                    {formatNumber(data.GDR)}
                                </td>
                            </tr>
                        ))
                    ) : (
                        <tr className="bg-white border border-gray-500 border-solid">
                            <td colSpan="8" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                        </tr>
                    )}
                </tbody>
            </table>
        </div>

    );
}
