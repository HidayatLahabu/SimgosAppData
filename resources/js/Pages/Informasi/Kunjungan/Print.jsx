import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import { formatDate } from '@/utils/formatDate';
import PrintHarian from './PrintHarian';
import PrintMingguan from './PrintMingguan';
import PrintBulanan from './PrintBulanan';
import PrintTahunan from './PrintTahunan';

export default function Print({
    harian,
    mingguan,
    bulanan,
    tahunan,
    dariTanggal,
    sampaiTanggal,
    namaRuangan,
}) {

    useEffect(() => {
        import('@/../../resources/css/print.css');
    }, []);

    // Periksa apakah dariTanggal sama dengan sampaiTanggal
    const isSingleDay = new Date(dariTanggal).toLocaleDateString() === new Date(sampaiTanggal).toLocaleDateString();

    return (
        <div className="h-screen w-screen bg-white">
            <Head title="Laporan" />

            <div className="content">
                <div className="w-full mx-auto sm:px-6 lg:px-5">
                    <div className="w-full bg-white overflow-hidden">
                        <div className="p-2 bg-white">
                            <h1 className="text-center font-bold text-2xl">
                                INFORMASI KUNJUNGAN RAWAT JALAN
                            </h1>
                            <h2 className="text-center font-bold text-2xl uppercase">
                                RUANGAN LAYANAN : {namaRuangan}
                            </h2>
                            <p className="text-center font-bold text-2xl mb-2">
                                {new Date(dariTanggal).toLocaleDateString() === new Date(sampaiTanggal).toLocaleDateString()
                                    ? `Tanggal : ${formatDate(sampaiTanggal)}`
                                    : `Selang Tanggal : ${formatDate(dariTanggal)} s.d ${formatDate(sampaiTanggal)}`}
                            </p>
                            {/* Tampilkan hanya PrintHarian jika dariTanggal === sampaiTanggal */}
                            {isSingleDay ? (
                                <PrintHarian
                                    harian={harian}
                                    dariTanggal={dariTanggal}
                                    sampaiTanggal={sampaiTanggal}
                                />
                            ) : (
                                <>
                                    <div className="flex flex-wrap w-full">
                                        <div className="w-1/2">
                                            <PrintHarian
                                                harian={harian}
                                                dariTanggal={dariTanggal}
                                                sampaiTanggal={sampaiTanggal}
                                            />
                                        </div>
                                        <div className="w-1/2">
                                            <PrintMingguan
                                                mingguan={mingguan}
                                            />

                                            <PrintBulanan
                                                bulanan={bulanan}
                                            />

                                            <PrintTahunan
                                                tahunan={tahunan}
                                            />
                                        </div>
                                    </div>
                                </>
                            )}

                        </div>
                    </div>
                </div>
                <footer className="bg-white text-black text-sm">
                    <div className="text-center">
                        <p>&copy; 2024 - {new Date().getFullYear()} Hidayat - Tim IT RSUD Dr. M. M. Dunda Limboto.</p>
                    </div>
                </footer>
            </div>
        </div>
    );
}
