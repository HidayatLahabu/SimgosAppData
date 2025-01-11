import React from 'react';

export default function PrintMingguan({
    mingguan,
}) {


    return (
        <div className="overflow-auto mt-2 ml-5">
            <h1 className="text-center font-bold text-2xl">
                MINGGUAN
            </h1>
            <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                    <tr>
                        <th className="px-3 py-2 border border-gray-500 border-solid">TAHUN</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">MINGGU</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">JENIS KUNJUNGAN</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">SUB UNIT</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-right">JUMLAH</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">TANGGAL UPDATE</th>
                    </tr>
                </thead>
                <tbody>
                    {mingguan.map((item, key) => (
                        <tr key={item.minggu} className="border-b bg-white dark:border-gray-500">
                            <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                {item.tahun}
                            </td>
                            <td className="px-3 py-2 border border-gray-500 border-solid">
                                {item.minggu}
                            </td>
                            <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                {item.jenisKunjungan}
                            </td>
                            <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                {item.subUnit}
                            </td>
                            <td className="px-3 py-2 border text-nowrap text-right border-gray-500 border-solid">
                                {item.jumlah}
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

