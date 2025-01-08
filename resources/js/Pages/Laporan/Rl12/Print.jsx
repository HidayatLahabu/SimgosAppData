import React, { useEffect } from 'react';
import { Head } from "@inertiajs/react";
import Kunjungan from './Kunjungan';
import PrintBulan from './PrintBulan';
import PrintTriwulan from './PrintTriwulan';
import PrintSemester from './PrintSemester';
import PrintTahun from './PrintTahun';

export default function Print({
    tahun,
    ttidur,
    awalTahun,
    masukTahun,
    keluarTahun,
    lebih48Tahun,
    kurang48Tahun,
    sisaTahun,
    statistikBulanan,
    statistikTriwulan,
    statistikSemester,
    statistikTahun,
}) {

    useEffect(() => {
        import('@/../../resources/css/print.css');
    }, []);

    return (
        <div className="h-screen w-screen bg-white">
            <Head title="Laporan" />

            <div className="content">
                <div className="w-full mx-auto sm:px-6 lg:px-5">
                    <div className="w-full bg-white overflow-hidden">
                        <div className="p-2 bg-white">
                            <div className="overflow-auto">
                                <h1 className="text-center font-bold text-2xl">
                                    LAPORAN RL 1.2 <br />TAHUN {tahun}
                                </h1>

                                <Kunjungan
                                    tahun={tahun}
                                    ttidur={ttidur}
                                    awalTahun={awalTahun}
                                    masukTahun={masukTahun}
                                    keluarTahun={keluarTahun}
                                    lebih48Tahun={lebih48Tahun}
                                    kurang48Tahun={kurang48Tahun}
                                    sisaTahun={sisaTahun}
                                />

                                <PrintBulan
                                    statistikBulanan={statistikBulanan}
                                />

                                <PrintTriwulan
                                    statistikTriwulan={statistikTriwulan}
                                />

                                <PrintSemester
                                    statistikSemester={statistikSemester}
                                />

                                <PrintTahun
                                    statistikTahun={statistikTahun}
                                />

                            </div>
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
