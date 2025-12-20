import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import ButtonBack from '@/Components/Button/ButtonBack';
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";
import TableDetailRme from './TableDetailRme';
import ButtonEdit from '@/Components/Button/ButtonEditKunjungan';

export default function Detail({
    auth,
    detail,
    kunjunganId,
    triage,
    rekonsiliasiObatAdmisi,
    rekonsiliasiObatTransfer,
    rekonsiliasiObatDischarge,
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
    pemeriksaanFungsional,
    pemeriksaanFisik,
    pemeriksaanKepala,
    pemeriksaanMata,
    pemeriksaanTelinga,
    pemeriksaanHidung,
    pemeriksaanRambut,
    pemeriksaanBibir,
    pemeriksaanGigiGeligi,
    pemeriksaanLidah,
    pemeriksaanLangitLangit,
    pemeriksaanLeher,
    pemeriksaanTenggorokan,
    pemeriksaanTonsil,
    pemeriksaanDada,
    pemeriksaanPayudara,
    pemeriksaanPunggung,
    pemeriksaanPerut,
    pemeriksaanGenital,
    pemeriksaanAnus,
    pemeriksaanLenganAtas,
    pemeriksaanLenganBawah,
    pemeriksaanJariTangan,
    pemeriksaanKukuTangan,
    pemeriksaanPersendianTangan,
    pemeriksaanTungkaiAtas,
    pemeriksaanTungkaiBawah,
    pemeriksaanJariKaki,
    pemeriksaanKukuKaki,
    pemeriksaanPersendianKaki,
    pemeriksaanFaring,
    pemeriksaanSaluranCernahBawah,
    pemeriksaanSaluranCernahAtas,
    pemeriksaanEeg,
    pemeriksaanEmg,
    pemeriksaanRavenTest,
    pemeriksaanCatClams,
    pemeriksaanTransfusiDarah,
    pemeriksaanAsessmentMChat,
    pemeriksaanEkg,
    penilaianFisik,
    penilaianNyeri,
    penilaianStatusPediatrik,
    penilaianDiagnosis,
    penilaianSkalaMorse,
    penilaianSkalaHumptyDumpty,
    penilaianEpfra,
    penilaianGetupGo,
    penilaianDekubitus,
    penilaianBallanceCairan,
    diagnosa,
    rencanaTerapi,
    jadwalKontrol,
    perencanaanRawatInap,
    dischargePlanningSkrining,
    dischargePlanningFaktorRisiko,
    cppt,
    pemantuanHDIntradialitik,
    tindakanAbci,
    tindakanMmpi,
}) {

    const headers = [
        { name: "NO" },
        { name: "COLUMN NAME" },
        { name: "VALUE" },
    ];

    // Generate detailData dynamically from the detail object
    const detailData = Object.keys(detail).map((key) => ({
        uraian: key, // Keep the original column name as it is
        value: detail[key],
    }));

    const filteredDetailData = detailData.filter((item) => {
        return item.value !== null && item.value !== undefined && item.value !== "" &&
            (item.value !== 0 || item.uraian === "STATUS_KUNJUNGAN" || item.uraian === "STATUS_AKTIFITAS_KUNJUNGAN");
    });

    // Specify how many rows per table
    const rowsPerTable = Math.ceil(filteredDetailData.length / 2);

    // Split the data into groups
    const tables = [];
    for (let i = 0; i < filteredDetailData.length; i += rowsPerTable) {
        tables.push(filteredDetailData.slice(i, i + rowsPerTable));
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
                                        href={route("kunjungan.index")}
                                    />
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">
                                        DATA DETAIL KUNJUNGAN
                                    </h1>
                                    <ButtonEdit
                                        href={route('kunjungan.edit', kunjunganId)}
                                        label="Update Kunjungan"
                                    />
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
                                                                className={`${index === 0 ? 'w-[5%]' : index === 1 ? 'w-[35%]' : 'w-[auto]'} ${header.className || ""}`}
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
                                                                    detailItem.value == 1 ? "Baru" :
                                                                        detailItem.value == 0 ? "Lama" :
                                                                            detailItem.value
                                                                ) : detailItem.uraian === "STATUS_AKTIFITAS_KUNJUNGAN" ? (
                                                                    detailItem.value === 0 ? "Batal Kunjungan" :
                                                                        detailItem.value === 1 ? "Pasien Berada di ruangan ini/Sedang dilayani" :
                                                                            detailItem.value === 2 ? "Selesai" :
                                                                                detailItem.value
                                                                ) : detailItem.value}
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
                rekonsiliasiObatAdmisi={rekonsiliasiObatAdmisi}
                rekonsiliasiObatTransfer={rekonsiliasiObatTransfer}
                rekonsiliasiObatDischarge={rekonsiliasiObatDischarge}
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
                pemeriksaanFungsional={pemeriksaanFungsional}
                pemeriksaanFisik={pemeriksaanFisik}
                pemeriksaanKepala={pemeriksaanKepala}
                pemeriksaanMata={pemeriksaanMata}
                pemeriksaanTelinga={pemeriksaanTelinga}
                pemeriksaanHidung={pemeriksaanHidung}
                pemeriksaanRambut={pemeriksaanRambut}
                pemeriksaanBibir={pemeriksaanBibir}
                pemeriksaanGigiGeligi={pemeriksaanGigiGeligi}
                pemeriksaanLidah={pemeriksaanLidah}
                pemeriksaanLangitLangit={pemeriksaanLangitLangit}
                pemeriksaanLeher={pemeriksaanLeher}
                pemeriksaanTenggorokan={pemeriksaanTenggorokan}
                pemeriksaanTonsil={pemeriksaanTonsil}
                pemeriksaanDada={pemeriksaanDada}
                pemeriksaanPayudara={pemeriksaanPayudara}
                pemeriksaanPunggung={pemeriksaanPunggung}
                pemeriksaanPerut={pemeriksaanPerut}
                pemeriksaanGenital={pemeriksaanGenital}
                pemeriksaanAnus={pemeriksaanAnus}
                pemeriksaanLenganAtas={pemeriksaanLenganAtas}
                pemeriksaanLenganBawah={pemeriksaanLenganBawah}
                pemeriksaanJariTangan={pemeriksaanJariTangan}
                pemeriksaanKukuTangan={pemeriksaanKukuTangan}
                pemeriksaanPersendianTangan={pemeriksaanPersendianTangan}
                pemeriksaanTungkaiAtas={pemeriksaanTungkaiAtas}
                pemeriksaanTungkaiBawah={pemeriksaanTungkaiBawah}
                pemeriksaanJariKaki={pemeriksaanJariKaki}
                pemeriksaanKukuKaki={pemeriksaanKukuKaki}
                pemeriksaanPersendianKaki={pemeriksaanPersendianKaki}
                pemeriksaanFaring={pemeriksaanFaring}
                pemeriksaanSaluranCernahBawah={pemeriksaanSaluranCernahBawah}
                pemeriksaanSaluranCernahAtas={pemeriksaanSaluranCernahAtas}
                pemeriksaanEeg={pemeriksaanEeg}
                pemeriksaanEmg={pemeriksaanEmg}
                pemeriksaanRavenTest={pemeriksaanRavenTest}
                pemeriksaanCatClams={pemeriksaanCatClams}
                pemeriksaanTransfusiDarah={pemeriksaanTransfusiDarah}
                pemeriksaanAsessmentMChat={pemeriksaanAsessmentMChat}
                pemeriksaanEkg={pemeriksaanEkg}
                penilaianFisik={penilaianFisik}
                penilaianNyeri={penilaianNyeri}
                penilaianStatusPediatrik={penilaianStatusPediatrik}
                penilaianDiagnosis={penilaianDiagnosis}
                penilaianSkalaMorse={penilaianSkalaMorse}
                penilaianSkalaHumptyDumpty={penilaianSkalaHumptyDumpty}
                penilaianEpfra={penilaianEpfra}
                penilaianGetupGo={penilaianGetupGo}
                penilaianDekubitus={penilaianDekubitus}
                penilaianBallanceCairan={penilaianBallanceCairan}
                diagnosa={diagnosa}
                rencanaTerapi={rencanaTerapi}
                jadwalKontrol={jadwalKontrol}
                perencanaanRawatInap={perencanaanRawatInap}
                dischargePlanningSkrining={dischargePlanningSkrining}
                dischargePlanningFaktorRisiko={dischargePlanningFaktorRisiko}
                cppt={cppt}
                pemantuanHDIntradialitik={pemantuanHDIntradialitik}
                tindakanAbci={tindakanAbci}
                tindakanMmpi={tindakanMmpi}
            />

        </AuthenticatedLayout>
    );
}
