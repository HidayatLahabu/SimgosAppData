import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/ButtonBack';
import ButtonDetail from "@/Components/ButtonDetail";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import TableCellMenu from "@/Components/TableCellMenu";
import InformasiKunjungan from "./InfoKunjungan";

export default function TablePemeriksaan({
    auth,
    dataTable,
    nomorKunjungan,
    nomorPendaftaran,
    namaPasien,
    normPasien,
    ruanganTujuan,
    statusKunjungan,
    tanggalKeluar,
    dpjp,
    idKepala,
    idMata,
    idTelinga,
    idHidung,
    idRambut,
    idBibir,
    idGigiGeliga,
    idLidah,
    idLangitLangit,
    idLeher,
    idTenggorokan,
    idTonsil,
    idDada,
    idPayudara,
    idPunggung,
    idPerut,
    idGenital,
    idAnus,
    idLenganAtas,
    idLenganBawah,
    idJariTangan,
    idKukuTangan,
    idPersendianTangan,
    idTungkaiAtas,
    idTungkaiBawah,
    idJariKaki,
    idKukuKaki,
    idPersendianKaki,
    idFaring,
    idSaluranCernahBawah,
    idSaluranCernahAtas,
}) {

    const headers = [
        { name: "PEMERIKSAAN ANATOMI", className: "text-left, w-[70%]" },
        { name: "INPUT DATA", className: "text-left, w-[auto]" },
    ];

    const rows = [
        { label: "PEMERIKSAAN KEPALA", data: idKepala, routeName: "kunjungan.pemeriksaanKepala" },
        { label: "PEMERIKSAAN MATA", data: idMata, routeName: "kunjungan.pemeriksaanMata" },
        { label: "PEMERIKSAAN TELINGA", data: idTelinga, routeName: "kunjungan.pemeriksaanTelinga" },
        { label: "PEMERIKSAAN HIDUNG", data: idHidung, routeName: "kunjungan.pemeriksaanHidung" },
        { label: "PEMERIKSAAN RAMBUT", data: idRambut, routeName: "kunjungan.pemeriksaanRambut" },
        { label: "PEMERIKSAAN BIBIR", data: idBibir, routeName: "kunjungan.pemeriksaanBibir" },
        { label: "PEMERIKSAAN GIGI GELIGA", data: idGigiGeliga, routeName: "kunjungan.pemeriksaanGigiGeliga" },
        { label: "PEMERIKSAAN LIDAH", data: idLidah, routeName: "kunjungan.pemeriksaanLidah" },
        { label: "PEMERIKSAAN LANGIT-LANGIT", data: idLangitLangit, routeName: "kunjungan.pemeriksaanLangitLangit" },
        { label: "PEMERIKSAAN LEHER", data: idLeher, routeName: "kunjungan.pemeriksaanLeher" },
        { label: "PEMERIKSAAN TENGGOROKAN", data: idTenggorokan, routeName: "kunjungan.pemeriksaanTenggorokan" },
        { label: "PEMERIKSAAN TONSIL", data: idTonsil, routeName: "kunjungan.pemeriksaanTonsil" },
        { label: "PEMERIKSAAN DADA", data: idDada, routeName: "kunjungan.pemeriksaanDada" },
        { label: "PEMERIKSAAN PAYUDARA", data: idPayudara, routeName: "kunjungan.pemeriksaanPayudara" },
        { label: "PEMERIKSAAN PUNGGUNG", data: idPunggung, routeName: "kunjungan.pemeriksaanPunggung" },
        { label: "PEMERIKSAAN PERUT", data: idPerut, routeName: "kunjungan.pemeriksaanPerut" },
        { label: "PEMERIKSAAN GENITAL", data: idGenital, routeName: "kunjungan.pemeriksaanGenital" },
        { label: "PEMERIKSAAN ANUS", data: idAnus, routeName: "kunjungan.pemeriksaanAnus" },
        { label: "PEMERIKSAAN LENGAN ATAS", data: idLenganAtas, routeName: "kunjungan.pemeriksaanLenganAtas" },
        { label: "PEMERIKSAAN LENGAN BAWAH", data: idLenganBawah, routeName: "kunjungan.pemeriksaanLenganBawah" },
        { label: "PEMERIKSAAN JARI TANGAN", data: idJariTangan, routeName: "kunjungan.pemeriksaanJariTangan" },
        { label: "PEMERIKSAAN KUKU TANGAN", data: idKukuTangan, routeName: "kunjungan.pemeriksaanKukuTangan" },
        { label: "PEMERIKSAAN PERSENDIAN TANGAN", data: idPersendianTangan, routeName: "kunjungan.pemeriksaanPersediaanTangan" },
        { label: "PEMERIKSAAN TUNGKAI ATAS", data: idTungkaiAtas, routeName: "kunjungan.pemeriksaanTungkaiAtas" },
        { label: "PEMERIKSAAN TUNGKAI BAWAH", data: idTungkaiBawah, routeName: "kunjungan.pemeriksaanTungkaiBawah" },
        { label: "PEMERIKSAAN JARI KAKI", data: idJariKaki, routeName: "kunjungan.pemeriksaanJariKaki" },
        { label: "PEMERIKSAAN KUKU KAKI", data: idKukuKaki, routeName: "kunjungan.pemeriksaanKukuKaki" },
        { label: "PEMERIKSAAN PERSENDIAN KAKI", data: idPersendianKaki, routeName: "kunjungan.pemeriksaanPersendianKaki" },
        { label: "PEMERIKSAAN FARING", data: idFaring, routeName: "kunjungan.pemeriksaanFaring" },
        { label: "PEMERIKSAAN SALURAN CERNAH ATAS", data: idSaluranCernahAtas, routeName: "kunjungan.pemeriksaanSaluranCernahAtas" },
        { label: "PEMERIKSAAN SALURAN CERNAH BAWAH", data: idSaluranCernahBawah, routeName: "kunjungan.pemeriksaanSaluranCernahBawah" },
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
        <AuthenticatedLayout user={auth.user}>
            <Head title="Pendaftaran" />
            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="relative flex items-center justify-between pb-2">
                                    <ButtonBack href={route("kunjungan.detail", { id: nomorKunjungan })} />
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">DAFTAR CPPT</h1>
                                </div>

                                {/* Informasi Detail */}
                                <InformasiKunjungan
                                    nomorPendaftaran={nomorPendaftaran}
                                    nomorKunjungan={nomorKunjungan}
                                    normPasien={normPasien}
                                    namaPasien={namaPasien}
                                    ruanganTujuan={ruanganTujuan}
                                    dpjp={dpjp}
                                    tanggalKeluar={tanggalKeluar}
                                    statusKunjungan={statusKunjungan}
                                />

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
            </div>
        </AuthenticatedLayout>
    );
}
