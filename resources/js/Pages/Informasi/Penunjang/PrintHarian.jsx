import React from 'react';
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';

export default function PrintHarian({
    harian,
}) {

    return (
        <div className="overflow-auto mt-2 mr-5">
            <h1 className="text-center font-bold text-2xl">
                HARIAN
            </h1>
            <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                    <tr>
                        <th className="px-3 py-2 border border-gray-500 border-solid w-[15%]">TANGGAL</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">SUB UNIT</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-right w-[15%]">JUMLAH</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid w-[25%]">LAST UPDATE</th>
                    </tr>
                </thead>
                <tbody>
                    {harian.map((item, key) => (
                        <tr key={item.tanggal} className="border-b bg-white dark:border-gray-500">
                            <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                {formatDate(item.tanggal)}
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

