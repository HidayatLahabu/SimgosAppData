import React, { useEffect } from 'react';
import { formatNumber } from "@/utils/formatNumber";


export default function PrintSemester({
    statistikTahun
}) {

    useEffect(() => {
        import('@/../../resources/css/print.css');
    }, []);

    return (
        <div className="py-3 overflow-auto">
            <h1 className="text-center font-bold">
                Indikator Pelayanan Per Tahun
            </h1>
            <table className="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-900 border border-gray-500">
                <thead className="uppercase font-bold text-gray-900 bg-gray-300 dark:text-gray-900 border border-gray-500">
                    <tr>
                        <th className="px-3 py-2 border border-gray-500 border-solid">Bed Occupancy Rate</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">Average Length of Stay</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">Bed Turn Over</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">Turn Over Interval</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">Net Death Rate</th>
                        <th className="px-3 py-2 border border-gray-500 border-solid">Gross Death Rate</th>
                    </tr>
                </thead>

                <tbody>
                    <tr className="border-b bg-white dark:border-gray-500">
                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                            {statistikTahun.BOR}
                        </td>
                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                            {statistikTahun.AVLOS}
                        </td>
                        <td className="px-3 py-2 text-nowrap border border-gray-500 border-solid">
                            {statistikTahun.BTO}
                        </td>
                        <td className="px-3 py-2 border border-gray-500 border-solid">
                            {statistikTahun.TOI}
                        </td>
                        <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                            {statistikTahun.NDR}
                        </td>
                        <td className="px-3 py-2 text-wrap border border-gray-500 border-solid">
                            {statistikTahun.GDR}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    );
}
