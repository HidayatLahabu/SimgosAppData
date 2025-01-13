import React from 'react';
import { formatDate } from '@/utils/formatDate';
import { formatRibuan } from '@/utils/formatRibuan';

export default function PrintHarian({
    kunjunganHarian,
    rujukanHarian,
}) {

    return (
        <div className="overflow-auto">

            <div className="flex space-x-4">
                <div className="w-1/2">
                    <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                        <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                            <tr>
                                <th className="px-3 py-2 border border-gray-500 border-solid">TANGGAL</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RAWAT JALAN</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RAWAT DARURAT</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RAWAT INAP</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid">TANGGAL UPDATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            {kunjunganHarian.map((item, key) => (
                                <tr key={item.tanggal} className="border-b bg-white dark:border-gray-500">
                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                        {formatDate(item.tanggal)}
                                    </td>
                                    <td className="px-3 py-2 text-nowrap text-right border border-gray-500 border-solid">
                                        {formatRibuan(item.rajal)}
                                    </td>
                                    <td className="px-3 py-2 text-nowrap text-right border border-gray-500 border-solid">
                                        {formatRibuan(item.darurat)}
                                    </td>
                                    <td className="px-3 py-2 border text-nowrap text-right border-gray-500 border-solid">
                                        {formatRibuan(item.ranap)}
                                    </td>
                                    <td className="px-3 py-2 border border-gray-500 border-solid">
                                        {item.tanggalUpdated}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <div className="w-1/2">
                    <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                        <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                            <tr>
                                <th className="px-3 py-2 border border-gray-500 border-solid">TANGGAL</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RUJUKAN MASUK</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RUJUKAN KELUAR</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RUJUKAN BALIK</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid">TANGGAL UPDATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            {rujukanHarian.map((item, key) => (
                                <tr key={item.tanggal} className="border-b bg-white dark:border-gray-500">
                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                        {formatDate(item.tanggal)}
                                    </td>
                                    <td className="px-3 py-2 text-nowrap text-right border border-gray-500 border-solid">
                                        {formatRibuan(item.masuk)}
                                    </td>
                                    <td className="px-3 py-2 text-nowrap text-right border border-gray-500 border-solid">
                                        {formatRibuan(item.keluar)}
                                    </td>
                                    <td className="px-3 py-2 border text-right border-gray-500 border-solid">
                                        {formatRibuan(item.balik)}
                                    </td>
                                    <td className="px-3 py-2 border border-gray-500 border-solid">
                                        {item.tanggalUpdated}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}

