import React from 'react';
import { formatNumber } from "@/utils/formatNumber";

export default function ThisYear({
    tahunIni,
    ttidurIni,
    awalTahunIni,
    masukTahunIni,
    keluarTahunIni,
    lebih48TahunIni,
    kurang48TahunIni,
    sisaTahunIni,
    jumlahKunjunganTahunIni,
    statistikTahunIni,
    //tahun lalu
    tahunLalu,
    ttidurLalu,
    awalTahunLalu,
    masukTahunLalu,
    keluarTahunLalu,
    lebih48TahunLalu,
    kurang48TahunLalu,
    sisaTahunLalu,
    jumlahKunjunganTahunLalu,
    statistikTahunLalu,
}) {

    return (
        <div className="py-5 flex flex-wrap w-full">
            <div className="max-w-full mx-auto sm:pl-5 sm:pr-2 lg:pl-5 lg:pr-2 w-full">
                <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                    <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                        <div className="bg-white dark:bg-indigo-950 rounded-lg shadow-lg p-2">
                            <table className="min-w-full table-auto mt-2 border border-gray-500 dark:border-gray-600">
                                <thead>
                                    <tr className='h-10'>
                                        <th className="text-normal font-semibold uppercase px-2 py-1 text-yellow-500 dark:bg-indigo-900">
                                            URAIAN
                                        </th>
                                        <th className="text-normal font-semibold uppercase px-2 py-1 text-yellow-500 dark:bg-indigo-900 border border-gray-500 dark:border-gray-600">
                                            Tahun {tahunIni}
                                        </th>
                                        <th className="text-normal font-semibold uppercase px-2 py-1 text-yellow-500 dark:bg-indigo-900">
                                            Tahun {tahunLalu}
                                        </th>
                                        <th className="text-normal font-semibold uppercase px-2 py-1 text-yellow-500 dark:bg-indigo-900">
                                            Naik/Turun
                                        </th>
                                        <th className="text-normal font-semibold uppercase px-2 py-1 text-yellow-500 dark:bg-indigo-900">
                                            PERSENTASE Naik/Turun
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Jumlah Tempat Tidur</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{ttidurIni}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{ttidurLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{ttidurIni - ttidurLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {ttidurLalu !== 0 ? formatNumber(((ttidurIni - ttidurLalu) / ttidurLalu) * 100) : 0} %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Bed Turn Over</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{statistikTahunIni.BOR}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{statistikTahunLalu.BOR}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{formatNumber(statistikTahunIni.BOR - statistikTahunLalu.BOR)}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {statistikTahunIni.BOR !== 0 ? formatNumber(((statistikTahunLalu.BOR - statistikTahunLalu.BOR) / statistikTahunLalu.BOR) * 100) : 0}  %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Average Length of Stay</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{statistikTahunIni.AVLOS}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{statistikTahunLalu.AVLOS}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{formatNumber(statistikTahunIni.AVLOS - statistikTahunLalu.AVLOS)}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {statistikTahunIni.AVLOS !== 0 ? formatNumber(((statistikTahunIni.AVLOS - statistikTahunLalu.AVLOS) / statistikTahunLalu.AVLOS) * 100) : 0}  %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Bed Turn Over</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{statistikTahunIni.BTO}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{statistikTahunLalu.BTO}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{formatNumber(statistikTahunIni.BTO - statistikTahunLalu.BTO)}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {statistikTahunIni.BTO !== 0 ? formatNumber(((statistikTahunIni.BTO - statistikTahunLalu.BTO) / statistikTahunLalu.BTO) * 100) : 0}  %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Turn Over Interval</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{statistikTahunIni.TOI}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{statistikTahunLalu.TOI}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{formatNumber(statistikTahunIni.TOI - statistikTahunLalu.TOI)}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {statistikTahunIni.TOI !== 0 ? formatNumber(((statistikTahunIni.TOI - statistikTahunLalu.TOI) / statistikTahunLalu.TOI) * 100) : 0}  %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Net Death Rate</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{statistikTahunIni.NDR}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{statistikTahunLalu.NDR}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{formatNumber(statistikTahunIni.NDR - statistikTahunLalu.NDR)}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {statistikTahunIni.NDR !== 0 ? formatNumber(((statistikTahunIni.NDR - statistikTahunLalu.NDR) / statistikTahunLalu.NDR) * 100) : 0}  %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Gross Death Rate</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{statistikTahunIni.GDR}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{statistikTahunLalu.GDR}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{formatNumber(statistikTahunIni.GDR - statistikTahunLalu.GDR)}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {statistikTahunIni.GDR !== 0 ? formatNumber(((statistikTahunIni.GDR - statistikTahunLalu.GDR) / statistikTahunLalu.GDR) * 100) : 0}  %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Rata-Rata Kunjungan</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{jumlahKunjunganTahunIni}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{jumlahKunjunganTahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{formatNumber(jumlahKunjunganTahunIni - jumlahKunjunganTahunLalu)}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {jumlahKunjunganTahunIni !== 0 ? formatNumber(((jumlahKunjunganTahunIni - jumlahKunjunganTahunLalu) / jumlahKunjunganTahunLalu) * 100) : 0}  %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Jumlah Awal</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{awalTahunIni}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{awalTahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{awalTahunIni - awalTahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {awalTahunIni !== 0 && awalTahunLalu !== 0
                                                ? formatNumber(((awalTahunIni - awalTahunLalu) / awalTahunLalu) * 100)
                                                : 0} %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Jumlah Masuk</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{masukTahunIni}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{masukTahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{masukTahunIni - masukTahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {masukTahunIni !== 0 && masukTahunLalu !== 0
                                                ? formatNumber(((masukTahunIni - masukTahunLalu) / masukTahunLalu) * 100)
                                                : 0} %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Jumlah Keluar</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{keluarTahunIni}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{keluarTahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{keluarTahunIni - keluarTahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {keluarTahunIni !== 0 && keluarTahunLalu !== 0
                                                ? formatNumber(((keluarTahunIni - keluarTahunLalu) / keluarTahunLalu) * 100)
                                                : 0} %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Meninggal Dirawat Lebih Dari 48 Jam</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{lebih48TahunIni}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{lebih48TahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{lebih48TahunIni - lebih48TahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {lebih48TahunIni !== 0 && lebih48TahunLalu !== 0
                                                ? formatNumber(((lebih48TahunIni - lebih48TahunLalu) / lebih48TahunLalu) * 100)
                                                : 0} %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Meninggal Dirawat Kurang Dari 48 Jam</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{kurang48TahunIni}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{kurang48TahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{kurang48TahunIni - kurang48TahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {kurang48TahunIni !== 0 && kurang48TahunLalu !== 0
                                                ? formatNumber(((kurang48TahunIni - kurang48TahunLalu) / kurang48TahunLalu) * 100)
                                                : 0} %
                                        </td>
                                    </tr>

                                    <tr className="hover:bg-gray-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 h-10">
                                        <td className="px-4 py-1 border border-gray-500 dark:border-gray-600">Pasien Dirawat</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{sisaTahunIni}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{sisaTahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">{sisaTahunIni - sisaTahunLalu}</td>
                                        <td className="px-4 py-1 text-center border border-gray-500 dark:border-gray-600">
                                            {sisaTahunIni !== 0 && sisaTahunLalu !== 0
                                                ? formatNumber(((sisaTahunIni - sisaTahunLalu) / sisaTahunLalu) * 100)
                                                : 0} %
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
