import React, { useEffect } from "react";
import { Head } from "@inertiajs/react";

export default function PrintRekap({ rekap, dari, sampai }) {
    useEffect(() => {
        import("@/../../resources/css/print.css");
        window.print();
    }, []);

    return (
        <div className="h-screen w-screen bg-white">
            <Head title="Rekap Tindakan Laboratorium" />

            <div className="content">
                <div className="w-full mx-auto sm:px-6 lg:px-5">
                    <div className="w-full bg-white overflow-hidden">
                        <div className="p-2 bg-white">
                            <h1 className="text-center font-bold text-2xl mb-2">
                                REKAP TINDAKAN LABORATORIUM PER JENIS KELAMIN
                            </h1>
                            <h2 className="text-center mb-4">
                                Selang Tanggal : {formatDate(dari)} s.d {formatDate(sampai)}
                            </h2>

                            <table className="w-full text-[12px] text-left border border-gray-500">
                                <thead className="bg-gray-300">
                                    <tr>
                                        <th className="px-3 py-2 border text-center border-gray-500 w-[4%]">NO</th>
                                        <th className="px-3 py-2 border border-gray-500">JENIS TINDAKAN</th>
                                        <th className="px-3 py-2 border text-center border-gray-500 w-[12%]">LAKI - LAKI</th>
                                        <th className="px-3 py-2 border text-center border-gray-500 w-[12%]">PEREMPUAN</th>
                                        <th className="px-3 py-2 border text-center border-gray-500 w-[12%]">JUMLAH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {rekap.map((row, idx) => (
                                        <tr key={idx}>
                                            <td className="px-3 py-2 border border-gray-500 text-center">
                                                {idx + 1}
                                            </td>
                                            <td className="px-3 py-2 border border-gray-500">{row.tindakan}</td>
                                            <td className="px-3 py-2 border border-gray-500 text-center">
                                                {row.laki}
                                            </td>
                                            <td className="px-3 py-2 border border-gray-500 text-center">
                                                {row.perempuan}
                                            </td>
                                            <td className="px-3 py-2 border border-gray-500 text-center">
                                                {row.total}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <footer className="bg-white text-black text-sm mt-5">
                    <div className="text-center">
                        <p>
                            &copy; 2024 - {new Date().getFullYear()} Hidayat - Tim IT RSUD
                            Dr. M. M. Dunda Limboto.
                        </p>
                    </div>
                </footer>
            </div>
        </div>
    );
}
