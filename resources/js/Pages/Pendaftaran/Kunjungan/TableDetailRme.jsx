import React from "react";
import { Link } from "@inertiajs/react";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function TableDetailRme({
    anamnesisDiperoleh,
    askep,
    cppt,
    diagnosa,
    jadwalKontrol,
    riwayatAlergi,
    riwayatLainnya,
    riwayatPemberianObat,
    riwayatPerjalananPenyakit,
    riwayatPenyakit,
    keluhanUtama,
    tandaVital }) {

    const headers = [
        { name: "MEDICAL RECORD", className: "text-left" },
        { name: "INPUT DATA", className: "text-left" },
    ];

    const rows = [
        { label: "ANAMNESIS DIPEROLEH", data: anamnesisDiperoleh, routeName: "kunjungan.anamnesisDiperoleh" },
        { label: "ASKEP", data: askep, routeName: "kunjungan.askep" },
        { label: "CPPT", data: cppt, routeName: "kunjungan.cppt" },
        { label: "DIAGNOSA", data: diagnosa, routeName: "kunjungan.diagnosa" },
        { label: "JADWAL KONTROL", data: jadwalKontrol, routeName: "kunjungan.jadwalKontrol" },
        { label: "KELUHAN UTAMA", data: keluhanUtama, routeName: "kunjungan.keluhanUtama" },
        { label: "RIWAYAT ALERGI", data: riwayatAlergi, routeName: "kunjungan.riwayatAlergi" },
        { label: "RIWAYAT LAINNYA", data: riwayatLainnya, routeName: "kunjungan.riwayatLainnya" },
        { label: "RIWAYAT PEMBERIAN OBAT", data: riwayatPemberianObat, routeName: "kunjungan.riwayatPemberianObat" },
        { label: "RIWAYAT PERJALANAN PENYAKIT", data: riwayatPerjalananPenyakit, routeName: "kunjungan.riwayatPerjalananPenyakit" },
        { label: "RIWAYAT PENYAKIT SEKARANG", data: riwayatPenyakit, routeName: "kunjungan.riwayatPenyakit" },
        { label: "TANDA VITAL", data: tandaVital, routeName: "kunjungan.tandaVital" },
    ];

    // Membagi rows menjadi dua bagian
    const midIndex = Math.ceil(rows.length / 2);
    const firstHalf = rows.slice(0, midIndex);
    const secondHalf = rows.slice(midIndex);

    const DataLink = ({ href }) => (
        href ? (
            <div className="flex items-center">
                <Link
                    href={href}
                    className="font-semibold text-gray-800 dark:text-green-500 hover:no-underline"
                >
                    ADA
                </Link>
                {/* Small faded text next to ADA, outside the link */}
                <span className="text-xs text-gray-500 ml-2">
                    Lihat Disini
                </span>
            </div>
        ) : (
            <span className="block font-semibold text-red-800 dark:text-red-500">
                BELUM ADA
            </span>
        )
    );

    // Fungsi untuk render tabel
    const renderTable = (dataRows) => (
        <Table>
            <TableHeader>
                <tr>
                    {headers.map((header, index) => (
                        <TableHeaderCell key={index} className={header.className}>
                            {header.name}
                        </TableHeaderCell>
                    ))}
                </tr>
            </TableHeader>
            <tbody>
                {dataRows.map((row, index) => (
                    <TableRow key={index}>
                        <TableCell>{row.label}</TableCell>
                        <TableCell>
                            {row.data ? (
                                <DataLink
                                    href={route(row.routeName, { id: row.data })}
                                    label={`Data ${row.label}`}
                                />
                            ) : (
                                <span className="text-red-500">BELUM ADA</span>
                            )}
                        </TableCell>
                    </TableRow>
                ))}
            </tbody>
        </Table>
    );

    return (
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-2">
            {/* Tabel Pertama */}
            <div className="overflow-auto w-full">{renderTable(firstHalf)}</div>

            {/* Tabel Kedua */}
            <div className="overflow-auto w-full">{renderTable(secondHalf)}</div>
        </div>
    );
}
