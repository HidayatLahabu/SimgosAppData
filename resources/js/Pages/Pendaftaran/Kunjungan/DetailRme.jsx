import React from "react";
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
        uraian: key,
        value: detail[key],
    }));

    // Filter out detailData with empty or whitespace values
    const filteredDetailData = detailData.filter((item) => {
        const value = String(item.value || "").trim();
        return value !== "";
    });

    // Tentukan jumlah row per tabel
    const rowsPerTable =
        filteredDetailData.length > 10
            ? Math.ceil(filteredDetailData.length / 2)
            : filteredDetailData.length;

    // Bagi data menjadi grup hanya jika jumlah row > 10
    const tables = filteredDetailData.length > 10 ? Array.from({
        length: Math.ceil(filteredDetailData.length / rowsPerTable),
    }, (_, i) => filteredDetailData.slice(i * rowsPerTable, (i + 1) * rowsPerTable))
        : [filteredDetailData];

    // Function to get the display value based on mappings
    function getDisplayValue(uraian, value) {
        const statusMapping = {
            "STATUS": {
                1: "Final",
                0: "Belum Final"
            },
            "INA_GROUPER": {
                1: "Final",
                0: "Belum Final"
            },
            "STATUS_MENTAL": {
                1: "Sadar dan orientasi baik",
                2: "Ada masalah perilaku",
                3: "Perilaku kekerasan yang dialami pasien sebelumnya"
            },
            "HUBUNGAN_PASIEN_DENGAN_KELUARGA": {
                1: "Baik",
                0: "Tidak Baik"
            },
            "TEMPAT_TINGGAL": {
                1: "Rumah",
                2: "Panti",
                3: "Lainnya"
            },
            "KEBIASAAN_BERIBADAH_TERATUR": {
                1: "Ya",
                2: "Tidak"
            },
            "NILAI_KEPERCAYAAN": {
                1: "Ada",
                0: "Tidak"
            },
            "PENGHASILAN_PERBULAN": {
                1: "< Rp.5.000.000",
                2: "Rp.5.000.000 s/d Rp.10.000.000",
                3: "> Rp.10.000.000"
            },
            "BEROBAT": {
                1: "Pernah Transfusi Darah",
                2: "Tidak Pernah Transfusi Darah"
            },
            "SPUTUM": {
                1: "Positif",
                2: "Negatif"
            },
            "STATUS_KONDISI_SOSIAL": {
                1: "Final",
                0: "Belum Final"
            },
            "STATUS_END_OF_LIFE": {
                1: "Final",
                0: "Belum Final"
            }
        };

        // Return the mapped value if exists, otherwise return the original value
        if (statusMapping[uraian]) {
            return statusMapping[uraian][value] || value;
        }

        return value; // Return the original value if no mapping is found
    }

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
                                        <div key={tableIndex} className="flex-1 shadow-md rounded-lg"
                                        >
                                            <Table>
                                                <TableHeader>
                                                    <tr>
                                                        {headers.map(
                                                            (header, index) => (
                                                                <TableHeaderCell key={index} className={`${index === 0 ? "w-[10%]" : index === 1 ? "w-[30%]" : "w-[60%]"} 
                                                                ${header.className || ""}`}
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
                                                    {tableData.map((detailItem, index) => (
                                                        <TableRow key={index} >
                                                            <TableCell>
                                                                {index + 1 + tableIndex * rowsPerTable}
                                                            </TableCell>
                                                            <TableCell>
                                                                {detailItem.uraian}
                                                            </TableCell>
                                                            <TableCell className="text-wrap">
                                                                {getDisplayValue(detailItem.uraian, detailItem.value)}
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
