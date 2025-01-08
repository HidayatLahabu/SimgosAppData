import React, { useEffect } from 'react';

export default function Kunjungan({
    tahun,
    ttidur,
    awalTahun,
    masukTahun,
    keluarTahun,
    lebih48Tahun,
    kurang48Tahun,
    sisaTahun,
}) {

    useEffect(() => {
        import('@/../../resources/css/print.css');
    }, []);

    return (
        <div className="py-3 overflow-auto">
            <h1 className="text-center font-bold">
                Data Kunjungan
            </h1>
            <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                <thead className="text-sm font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                    <tr>
                        <th className="px-3 py-2 border border-gray-500 border-solid">JUMLAH TEMPAT TIDUR</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">JUMLAH AWAL</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">JUMLAH MASUK</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">JUMLAH KELUAR</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">MENINGGAL &lt; 48</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">MENINGGAL &gt; 48</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">PASIEN DI RAWAT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr className="border-b bg-white dark:border-gray-500">
                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                            {ttidur}
                        </td>
                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                            {awalTahun}
                        </td>
                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                            {masukTahun}
                        </td>
                        <td className="px-3 py-2 border border-gray-500 border-solid">
                            {keluarTahun}
                        </td>
                        <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                            {lebih48Tahun}
                        </td>
                        <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                            {kurang48Tahun}
                        </td>
                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                            {sisaTahun}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    );
}
