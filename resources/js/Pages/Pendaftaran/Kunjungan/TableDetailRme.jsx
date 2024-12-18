import React from "react";
import { Link } from "@inertiajs/react";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function TableDetailRme({
    triage,
    askep,
    anamnesisDiperoleh,
    keluhanUtama,
    riwayatPenyakitSekarang,
    riwayatPenyakitDahulu,
    riwayatAlergi,
    riwayatPemberianObat,
    riwayatLainnya,
    faktorRisiko,
    riwayatPenyakitKeluarga,
    riwayatTuberkulosis,
    riwayatGinekologi,
    statusFungsional,
    hubunganPsikososial,
    edukasiPasienKeluarga,
    edukasiEmergency,
    edukasiEndOfLife,
    skriningGiziAwal,
    batuk,
    pemeriksaanUmum,
    pemeriksaanFisik,
    pemeriksaanAnatomi,
    cppt,
    diagnosa,
    jadwalKontrol,
}) {
    const headers = [
        { name: "MEDICAL RECORD", className: "text-left, w-[70%]" },
        { name: "INPUT DATA", className: "text-left, w-[auto]" },
    ];

    const rows = [
        { label: "TRIAGE", data: triage, routeName: "kunjungan.triage" },
        { label: "ASUHAN KEPERAWATAN", data: askep, routeName: "kunjungan.askep" },
        { label: "KELUHAN UTAMA", data: keluhanUtama, routeName: "kunjungan.keluhanUtama" },
        { label: "ANAMNESIS DIPEROLEH", data: anamnesisDiperoleh, routeName: "kunjungan.anamnesisDiperoleh" },
        { label: "RIWAYAT PENYAKIT SEKARANG", data: riwayatPenyakitSekarang, routeName: "kunjungan.riwayatPenyakitSekarang" },
        { label: "RIWAYAT PENYAKIT DAHULU", data: riwayatPenyakitDahulu, routeName: "kunjungan.riwayatPenyakitDahulu" },
        { label: "RIWAYAT ALERGI", data: riwayatAlergi, routeName: "kunjungan.riwayatAlergi" },
        { label: "RIWAYAT PEMBERIAN OBAT", data: riwayatPemberianObat, routeName: "kunjungan.riwayatPemberianObat" },
        { label: "RIWAYAT LAINNYA", data: riwayatLainnya, routeName: "kunjungan.riwayatLainnya" },
        { label: "FAKTOR RISIKO", data: faktorRisiko, routeName: "kunjungan.faktorRisiko" },
        { label: "RIWAYAT PENYAKIT KELUARGA", data: riwayatPenyakitKeluarga, routeName: "kunjungan.riwayatPenyakitKeluarga" },
        { label: "RIWAYAT TUBERKULOSIS", data: riwayatTuberkulosis, routeName: "kunjungan.riwayatTuberkulosis" },
        { label: "RIWAYAT GINEKOLOGI", data: riwayatGinekologi, routeName: "kunjungan.riwayatGinekologi" },
        { label: "STATUS FUNGSIONAL", data: statusFungsional, routeName: "kunjungan.statusFungsional" },
        { label: "HUBUNGAN STATUS PSIKOSOSIAL", data: hubunganPsikososial, routeName: "kunjungan.hubunganPsikososial" },
        { label: "EDUKASI PASIEN DAN KELUARGA", data: edukasiPasienKeluarga, routeName: "kunjungan.edukasiPasienKeluarga" },
        { label: "EDUKASI EMERGENCY", data: edukasiEmergency, routeName: "kunjungan.edukasiEmergency" },
        { label: "EDUKASI END OF LIFE", data: edukasiEndOfLife, routeName: "kunjungan.edukasiEndOfLife" },
        { label: "SKRINING GIZI AWAL", data: skriningGiziAwal, routeName: "kunjungan.skriningGiziAwal" },
        { label: "BATUK", data: batuk, routeName: "kunjungan.batuk" },
        { label: "PEMERIKSAAN UMUM", data: pemeriksaanUmum, routeName: "kunjungan.pemeriksaanUmum" },
        { label: "PEMERIKSAAN FISIK", data: pemeriksaanFisik, routeName: "kunjungan.pemeriksaanUmum" },
        { label: "PEMERIKSAAN ANATOMI", data: pemeriksaanAnatomi, routeName: "kunjungan.pemeriksaanAnatomi" },
        { label: "CPPT", data: cppt, routeName: "kunjungan.cppt" },
        { label: "DIAGNOSA", data: diagnosa, routeName: "kunjungan.diagnosa" },
        { label: "JADWAL KONTROL", data: jadwalKontrol, routeName: "kunjungan.jadwalKontrol" },
    ];

    // Membagi rows menjadi tiga bagian
    const partSize = Math.ceil(rows.length / 3);
    const firstPart = rows.slice(0, partSize);
    const secondPart = rows.slice(partSize, partSize * 2);
    const thirdPart = rows.slice(partSize * 2);

    const DataLink = ({ href }) => (
        href ? (
            <div className="flex items-center">
                <Link
                    href={href}
                    className="font-semibold text-gray-800 dark:text-green-500 hover:no-underline"
                >
                    ADA
                    <span className="text-xs text-gray-500 ml-2">
                        Lihat Disini
                    </span>
                </Link>

            </div>
        ) : (
            <span className="block font-semibold text-red-800 dark:text-red-500">
                TIDAK ADA
            </span>
        )
    );

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
                                <span className="text-red-500">TIDAK ADA</span>
                            )}
                        </TableCell>
                    </TableRow>
                ))}
            </tbody>
        </Table>
    );

    return (
        <div className="py-2">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="relative flex items-center justify-between pb-7 pt-2">
                            <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">
                                DATA MEDICAL RECORD
                            </h1>
                        </div>
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            {/* Tabel Pertama */}
                            <div className="overflow-auto w-full">{renderTable(firstPart)}</div>

                            {/* Tabel Kedua */}
                            <div className="overflow-auto w-full">{renderTable(secondPart)}</div>

                            {/* Tabel Ketiga */}
                            <div className="overflow-auto w-full">{renderTable(thirdPart)}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

