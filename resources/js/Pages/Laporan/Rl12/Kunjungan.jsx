import React, { useEffect } from 'react';
import { formatRibuan } from '@/utils/formatRibuan';

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
                        <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[12%]">JUMLAH AWAL</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[12%]">JUMLAH MASUK</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[12%]">JUMLAH KELUAR</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[12%]">MENINGGAL &lt; 48</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[12%]">MENINGGAL &gt; 48</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid text-center w-[12%]">PASIEN DI RAWAT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr className="border-b bg-white dark:border-gray-500">
                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                            {ttidur}
                        </td>
                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid text-center">
                            {formatRibuan(awalTahun)}
                        </td>
                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid text-center">
                            {formatRibuan(masukTahun)}
                        </td>
                        <td className="px-3 py-2 border border-gray-500 border-solid text-center">
                            {formatRibuan(keluarTahun)}
                        </td>
                        <td className="px-3 py-2 text-wrap border border-gray-500 border-solid text-center">
                            {formatRibuan(lebih48Tahun)}
                        </td>
                        <td className="px-3 py-2 text-wrap border border-gray-500 border-solid text-center">
                            {formatRibuan(kurang48Tahun)}
                        </td>
                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid text-center">
                            {formatRibuan(sisaTahun)}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    );
}
