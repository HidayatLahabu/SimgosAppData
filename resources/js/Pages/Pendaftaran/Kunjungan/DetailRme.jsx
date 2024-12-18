import React from "react";
import sanitizeHtml from "sanitize-html";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from "@/Components/ButtonBack";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import InformasiKunjungan from "./InfoKunjungan";

export default function Detail({
    auth,
    nomorKunjungan,
    nomorPendaftaran,
    namaPasien,
    normPasien,
    ruanganTujuan,
    statusKunjungan,
    tanggalKeluar,
    dpjp,
    detail,
    judulRme,
}) {
    const headers = [
        { name: "NO", className: "w-[5%]" },
        { name: "COLUMN NAME", className: "w-[30%]" },
        { name: "VALUE", className: "w-[auto]" },
    ];

    // Generate detailData dynamically from the detail object
    const detailData = Object.keys(detail).map((key) => ({
        uraian: key, // Keep the original column name as it is
        value: detail[key],
    }));

    // Filter out detailData with empty or whitespace values
    const filteredDetailData = detailData.filter((item) => {
        const value = String(item.value || "").trim(); // Convert value to string and trim whitespace
        return value !== ""; // Only include non-empty strings
    });

    // Tentukan jumlah row per tabel
    const rowsPerTable =
        filteredDetailData.length > 10
            ? Math.ceil(filteredDetailData.length / 2)
            : filteredDetailData.length;

    // Bagi data menjadi grup hanya jika jumlah row > 10
    const tables =
        filteredDetailData.length > 10
            ? Array.from(
                {
                    length: Math.ceil(
                        filteredDetailData.length / rowsPerTable
                    ),
                },
                (_, i) =>
                    filteredDetailData.slice(
                        i * rowsPerTable,
                        (i + 1) * rowsPerTable
                    )
            )
            : [filteredDetailData]; // Jika <= 10, tetap satu tabel

    // Function to sanitize HTML and strip all tags
    const sanitizeValue = (value) => {
        return sanitizeHtml(value, {
            allowedTags: [], // Remove all tags
            allowedAttributes: {}, // Remove all attributes
        });
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Pendaftaran" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="relative flex items-center justify-between pb-2">
                                    <ButtonBack
                                        href={route("kunjungan.detail", {
                                            id: nomorKunjungan,
                                        })}
                                    />
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">
                                        DATA DETAIL {judulRme}
                                    </h1>
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

                                <div className="flex flex-wrap gap-2">
                                    {tables.map((tableData, tableIndex) => (
                                        <div
                                            key={tableIndex}
                                            className="flex-1 shadow-md rounded-lg"
                                        >
                                            <Table>
                                                <TableHeader>
                                                    <tr>
                                                        {headers.map(
                                                            (header, index) => (
                                                                <TableHeaderCell
                                                                    key={index}
                                                                    className={`${index ===
                                                                        0
                                                                        ? "w-[10%]"
                                                                        : index ===
                                                                            1
                                                                            ? "w-[30%]"
                                                                            : "w-[60%]"
                                                                        } ${header.className ||
                                                                        ""
                                                                        }`}
                                                                >
                                                                    {
                                                                        header.name
                                                                    }
                                                                </TableHeaderCell>
                                                            )
                                                        )}
                                                    </tr>
                                                </TableHeader>
                                                <tbody>
                                                    {tableData.map(
                                                        (detailItem, index) => (
                                                            <TableRow
                                                                key={index}
                                                            >
                                                                <TableCell>
                                                                    {index +
                                                                        1 +
                                                                        tableIndex *
                                                                        rowsPerTable}
                                                                </TableCell>
                                                                <TableCell>
                                                                    {
                                                                        detailItem.uraian
                                                                    }
                                                                </TableCell>
                                                                <TableCell className="text-wrap">
                                                                    {detailItem.uraian ===
                                                                        "STATUS"
                                                                        ? detailItem.value ===
                                                                            1
                                                                            ? "Final"
                                                                            : detailItem.value ===
                                                                                0
                                                                                ? "Belum Final"
                                                                                : detailItem.value
                                                                        : detailItem.uraian ===
                                                                            "INA_GROUPER"
                                                                            ? detailItem.value ===
                                                                                1
                                                                                ? "Final"
                                                                                : detailItem.value ===
                                                                                    0
                                                                                    ? "Belum Final"
                                                                                    : detailItem.value
                                                                            : detailItem.uraian ===
                                                                                "STATUS_MENTAL"
                                                                                ? detailItem.value ===
                                                                                    1
                                                                                    ? "Sadar dan orientasi baik"
                                                                                    : detailItem.value ===
                                                                                        2
                                                                                        ? "Ada masalah perilaku"
                                                                                        : detailItem.value ===
                                                                                            3
                                                                                            ? "Perilaku kekerasan yang dialami pasien sebelumnya"
                                                                                            : detailItem.value
                                                                                : detailItem.uraian ===
                                                                                    "HUBUNGAN_PASIEN_DENGAN_KELUARGA"
                                                                                    ? detailItem.value ===
                                                                                        1
                                                                                        ? "Baik"
                                                                                        : detailItem.value ===
                                                                                            0
                                                                                            ? "Tidak Baik"
                                                                                            : detailItem.value
                                                                                    : detailItem.uraian ===
                                                                                        "TEMPAT_TINGGAL"
                                                                                        ? detailItem.value ===
                                                                                            1
                                                                                            ? "Rumah"
                                                                                            : detailItem.value ===
                                                                                                2
                                                                                                ? "Panti"
                                                                                                : detailItem.value ===
                                                                                                    3
                                                                                                    ? "Lainnya"
                                                                                                    : detailItem.value
                                                                                        : detailItem.uraian ===
                                                                                            "KEBIASAAN_BERIBADAH_TERATUR"
                                                                                            ? detailItem.value ===
                                                                                                1
                                                                                                ? "Ya"
                                                                                                : detailItem.value ===
                                                                                                    2
                                                                                                    ? "Tidak"
                                                                                                    : detailItem.value
                                                                                            : detailItem.uraian ===
                                                                                                "NILAI_KEPERCAYAAN"
                                                                                                ? detailItem.value ===
                                                                                                    1
                                                                                                    ? "Ada"
                                                                                                    : detailItem.value ===
                                                                                                        0
                                                                                                        ? "Tidak"
                                                                                                        : detailItem.value
                                                                                                : detailItem.uraian ===
                                                                                                    "PENGHASILAN_PERBULAN"
                                                                                                    ? detailItem.value ===
                                                                                                        1
                                                                                                        ? "< Rp.5.000.000"
                                                                                                        : detailItem.value ===
                                                                                                            2
                                                                                                            ? "Rp.5.000.000 s/d Rp.10.000.000"
                                                                                                            : detailItem.value ===
                                                                                                                3
                                                                                                                ? "> Rp.10.000.000"
                                                                                                                : detailItem.value
                                                                                                    : detailItem.uraian ===
                                                                                                        "BEROBAT"
                                                                                                        ? detailItem.value ===
                                                                                                            1
                                                                                                            ? "Pernah Transfusi Darah"
                                                                                                            : detailItem.value ===
                                                                                                                2
                                                                                                                ? "Tidak Pernah Transfusi Darah"
                                                                                                                : detailItem.value
                                                                                                        : detailItem.uraian ===
                                                                                                            "SPUTUM"
                                                                                                            ? detailItem.value ===
                                                                                                                1
                                                                                                                ? "Positif"
                                                                                                                : detailItem.value ===
                                                                                                                    2
                                                                                                                    ? "Negatif"
                                                                                                                    : detailItem.value
                                                                                                            : detailItem.uraian ===
                                                                                                                "STATUS_KONDISI_SOSIAL"
                                                                                                                ? detailItem.value ===
                                                                                                                    1
                                                                                                                    ? "Final"
                                                                                                                    : detailItem.value ===
                                                                                                                        0
                                                                                                                        ? "Belum Final"
                                                                                                                        : detailItem.value
                                                                                                                : detailItem.uraian ===
                                                                                                                    "STATUS_END_OF_LIFE"
                                                                                                                    ? detailItem.value ===
                                                                                                                        1
                                                                                                                        ? "Final"
                                                                                                                        : detailItem.value ===
                                                                                                                            0
                                                                                                                            ? "Belum Final"
                                                                                                                            : detailItem.value
                                                                                                                    : sanitizeValue(
                                                                                                                        detailItem.value
                                                                                                                    )}
                                                                </TableCell>
                                                            </TableRow>
                                                        )
                                                    )}
                                                </tbody>
                                            </Table>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
