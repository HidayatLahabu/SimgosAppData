import React from 'react';
import { formatRibuan } from '@/utils/formatRibuan';

export default function PrintBulanan({
    bulanan,
}) {

    const bulanNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    return (
        <div className="overflow-auto mt-5 ml-5">
            <h1 className="text-center font-bold text-2xl">
                BULANAN
            </h1>
            <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                    <tr>
                        <th className="px-3 py-2 border border-gray-500 border-solid w-[10%]">TAHUN</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid w-[15%]">BULAN</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">SUB UNIT</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-right w-[10%]">JUMLAH</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid w-[25%]">LAST UPDATE</th>
                    </tr>
                </thead>
                <tbody>
                    {bulanan.map((item, key) => (
                        <tr key={item.bulan} className="border-b bg-white dark:border-gray-500">
                            <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                {item.tahun}
                            </td>
                            <td className="px-3 py-2 border border-gray-500 border-solid">
                                {bulanNames[item.bulan - 1]}
                            </td>
                            <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                {item.subUnit}
                            </td>
                            <td className="px-3 py-2 border text-nowrap text-right border-gray-500 border-solid">
                                {formatRibuan(item.jumlah)}
                            </td>
                            <td className="px-3 py-2 border border-gray-500 border-solid">
                                {item.lastUpdated}
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}

