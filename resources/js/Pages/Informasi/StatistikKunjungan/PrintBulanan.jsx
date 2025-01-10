import React from 'react';

export default function PrintBulanan({
    kunjunganBulanan,
    rujukanBulanan,
}) {

    const bulanNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    return (
        <div className="overflow-auto mt-5">
            <h1 className="text-center font-bold text-2xl">
                LAPORAN KUNJUNGAN DAN RUJUKAN BULANAN
            </h1>

            <div className="flex space-x-4">
                <div className="w-1/2">
                    <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                        <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                            <tr>
                                <th className="px-3 py-2 border border-gray-500 border-solid">TAHUN</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid">BULAN</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RAWAT JALAN</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RAWAT DARURAT</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RAWAT INAP</th>
                            </tr>
                        </thead>
                        <tbody>
                            {kunjunganBulanan.map((item, key) => (
                                <tr key={item.minggu} className="border-b bg-white dark:border-gray-500">
                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                        {item.tahun}
                                    </td>
                                    <td className="px-3 py-2 border border-gray-500 border-solid">
                                        {bulanNames[item.bulan - 1]}
                                    </td>
                                    <td className="px-3 py-2 text-nowrap text-right border border-gray-500 border-solid">
                                        {item.rajal}
                                    </td>
                                    <td className="px-3 py-2 text-nowrap text-right border border-gray-500 border-solid">
                                        {item.darurat}
                                    </td>
                                    <td className="px-3 py-2 border text-nowrap text-right border-gray-500 border-solid">
                                        {item.ranap}
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
                                <th className="px-3 py-2 border border-gray-500 border-solid">TAHUN</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid">BULAN</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RUJUKAN MASUK</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RUJUKAN KELUAR</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RUJUKAN BALIK</th>
                            </tr>
                        </thead>
                        <tbody>
                            {rujukanBulanan.map((item, key) => (
                                <tr key={item.minggu} className="border-b bg-white dark:border-gray-500">
                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                        {item.tahun}
                                    </td>
                                    <td className="px-3 py-2 border border-gray-500 border-solid">
                                        {bulanNames[item.bulan - 1]}
                                    </td>
                                    <td className="px-3 py-2 text-nowrap text-right border border-gray-500 border-solid">
                                        {item.masuk}
                                    </td>
                                    <td className="px-3 py-2 text-nowrap text-right border border-gray-500 border-solid">
                                        {item.keluar}
                                    </td>
                                    <td className="px-3 py-2 border text-right border-gray-500 border-solid">
                                        {item.balik}
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

