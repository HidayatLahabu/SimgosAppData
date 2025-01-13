import React from 'react';

export default function PrintTahunan({
    rajalTahunan,
    ranapTahunan,
}) {


    return (
        <div className="overflow-auto mt-5">
            <div className="flex space-x-4">
                <div className="w-1/2">
                    <h2 className="text-center font-bold text-2xl">
                        RAWAT JALAN & RAWAT DARURAT TAHUNAN
                    </h2>

                    <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                        <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                            <tr>
                                <th className="px-3 py-2 border border-gray-500 border-solid">TAHUN</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RAWAT JALAN</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">RAWAT DARURAT</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">JUMLAH</th>
                            </tr>
                        </thead>
                        <tbody>
                            {rajalTahunan.map((item, key) => (
                                <tr key={item.tahun} className="border-b bg-white dark:border-gray-500">
                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                        {item.tahun}
                                    </td>
                                    <td className="px-3 py-2 text-nowrap text-right border border-gray-500 border-solid">
                                        {item.rajal}
                                    </td>
                                    <td className="px-3 py-2 text-nowrap text-right border border-gray-500 border-solid">
                                        {item.darurat}
                                    </td>
                                    <td className="px-3 py-2 border text-nowrap text-right border-gray-500 border-solid">
                                        {item.semua}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <div className="w-1/2">
                    <h2 className="text-center font-bold text-2xl">
                        RAWAT JALAN & RAWAT DARURAT TAHUNAN
                    </h2>

                    <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                        <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                            <tr>
                                <th className="px-3 py-2 border border-gray-500 border-solid">TAHUN</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">PASIEN MASUK</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">PASIEN DIRAWAT</th>
                                <th className="px-3 py-2 border border-gray-500 border-solid text-right">PASIEN KELUAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            {ranapTahunan.map((item, key) => (
                                <tr key={item.tahun} className="border-b bg-white dark:border-gray-500">
                                    <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                                        {item.tahun}
                                    </td>
                                    <td className="px-3 py-2 text-nowrap text-right border border-gray-500 border-solid">
                                        {item.masuk}
                                    </td>
                                    <td className="px-3 py-2 text-nowrap text-right border border-gray-500 border-solid">
                                        {item.dirawat}
                                    </td>
                                    <td className="px-3 py-2 border text-right border-gray-500 border-solid">
                                        {item.keluar}
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

