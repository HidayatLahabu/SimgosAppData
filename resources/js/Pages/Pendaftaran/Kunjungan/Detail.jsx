import React from 'react';
import sanitizeHtml from 'sanitize-html';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/ButtonBack';
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";
import TableDetailRme from './TableDetailRme';

export default function Detail({
    auth,
    detail,
    triage,
    askep,
    keluhanUtama,
    anamnesisDiperoleh,
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
    cppt,
    diagnosa,
    jadwalKontrol,
}) {

    const headers = [
        { name: "NO", className: "w-[5%]" },
        { name: "COLUMN NAME", className: "w-[40%]" },
        { name: "VALUE", className: "w-[auto]" },
    ];

    // Generate detailData dynamically from the detail object
    const detailData = Object.keys(detail).map((key) => ({
        uraian: key, // Keep the original column name as it is
        value: detail[key],
    }));

    // Specify how many rows per table
    const rowsPerTable = Math.ceil(detailData.length / 2);

    // Split the data into groups
    const tables = [];
    for (let i = 0; i < detailData.length; i += rowsPerTable) {
        tables.push(detailData.slice(i, i + rowsPerTable));
    }

    // Function to sanitize HTML and strip all tags
    const sanitizeValue = (value) => {
        return sanitizeHtml(value, {
            allowedTags: [], // Remove all tags
            allowedAttributes: {} // Remove all attributes
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
                                    <ButtonBack href={route("kunjungan.index")} />
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">DATA DETAIL KUNJUNGAN</h1>
                                </div>
                                <div className="flex flex-wrap gap-2">
                                    {tables.map((tableData, tableIndex) => (
                                        <div
                                            key={tableIndex}
                                            className="flex-1 shadow-md rounded-lg"
                                        >
                                            <Table>
                                                <TableHeader>
                                                    <tr>
                                                        {headers.map((header, index) => (
                                                            <TableHeaderCell
                                                                key={index}
                                                                className={`${index === 0 ? 'w-[10%]' : index === 1 ? 'w-[30%]' : 'w-[60%]'} ${header.className || ""}`}
                                                            >
                                                                {header.name}
                                                            </TableHeaderCell>
                                                        ))}
                                                    </tr>
                                                </TableHeader>
                                                <tbody>
                                                    {tableData.map((detailItem, index) => (
                                                        <TableRow key={index}>
                                                            <TableCell>{index + 1 + tableIndex * rowsPerTable}</TableCell>
                                                            <TableCell>{detailItem.uraian}</TableCell>
                                                            <TableCell className="text-wrap">
                                                                {detailItem.uraian === "STATUS_KUNJUNGAN" ? (
                                                                    detailItem.value === 1 ? "Baru" :
                                                                        detailItem.value === 0 ? "Lama" :
                                                                            detailItem.value
                                                                ) : detailItem.uraian === "STATUS_AKTIFITAS_KUNJUNGAN" ? (
                                                                    detailItem.value === 0 ? "Batal Kunjungan" :
                                                                        detailItem.value === 1 ? "Pasien Berada di ruangan ini/Sedang dilayani" :
                                                                            detailItem.value === 2 ? "Selesai" :
                                                                                detailItem.value
                                                                ) : sanitizeValue(detailItem.value)}
                                                            </TableCell>
                                                        </TableRow>
                                                    ))}
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

            <TableDetailRme
                triage={triage}
                askep={askep}
                keluhanUtama={keluhanUtama}
                anamnesisDiperoleh={anamnesisDiperoleh}
                riwayatPenyakitSekarang={riwayatPenyakitSekarang}
                riwayatPenyakitDahulu={riwayatPenyakitDahulu}
                riwayatAlergi={riwayatAlergi}
                riwayatPemberianObat={riwayatPemberianObat}
                riwayatLainnya={riwayatLainnya}
                faktorRisiko={faktorRisiko}
                riwayatPenyakitKeluarga={riwayatPenyakitKeluarga}
                riwayatTuberkulosis={riwayatTuberkulosis}
                riwayatGinekologi={riwayatGinekologi}
                statusFungsional={statusFungsional}
                hubunganPsikososial={hubunganPsikososial}
                edukasiPasienKeluarga={edukasiPasienKeluarga}
                edukasiEmergency={edukasiEmergency}
                edukasiEndOfLife={edukasiEndOfLife}
                skriningGiziAwal={skriningGiziAwal}
                batuk={batuk}
                pemeriksaanUmum={pemeriksaanUmum}
                pemeriksaanFisik={pemeriksaanFisik}
                cppt={cppt}
                diagnosa={diagnosa}
                jadwalKontrol={jadwalKontrol}
            />

        </AuthenticatedLayout>
    );
}
