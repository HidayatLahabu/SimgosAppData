import React from "react";
import { Link } from "@inertiajs/react";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function TableDetailRme({
    triage,
    rekonsiliasiObatAdmisi,
    rekonsiliasiObatTransfer,
    rekonsiliasiObatDischarge,
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
        { name: "MEDICAL RECORD", className: "text-left, w-[70%]" },
        { name: "INPUT DATA", className: "text-left, w-[auto]" },
    ];

    const rows = [
        { label: "TRIAGE", data: triage, routeName: "kunjungan.triage" },
        { label: "REKONSILIASI OBAT ADMISI", data: rekonsiliasiObatAdmisi, routeName: "kunjungan.rekonsiliasiObatAdmisi" },
        { label: "REKONSILIASI OBAT TRANSFER", data: rekonsiliasiObatTransfer, routeName: "kunjungan.rekonsiliasiObatTransfer" },
        { label: "REKONSILIASI OBAT DISCHARGE", data: rekonsiliasiObatDischarge, routeName: "kunjungan.rekonsiliasiObatDischarge" },
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
        { label: "PEMERIKSAAN UMUM TANDA VITAL", data: pemeriksaanUmum, routeName: "kunjungan.pemeriksaanUmum" },
        { label: "PEMERIKSAAN UMUM FUNGSIONAL", data: pemeriksaanFungsional, routeName: "kunjungan.pemeriksaanFungsional" },
        { label: "PEMERIKSAAN FISIK", data: pemeriksaanFisik, routeName: "kunjungan.pemeriksaanUmum" },
        { label: "PEMERIKSAAN KEPALA", data: pemeriksaanKepala, routeName: "kunjungan.pemeriksaanKepala" },
        { label: "PEMERIKSAAN MATA", data: pemeriksaanMata, routeName: "kunjungan.pemeriksaanMata" },
        { label: "PEMERIKSAAN TELINGA", data: pemeriksaanTelinga, routeName: "kunjungan.pemeriksaanTelinga" },
        { label: "PEMERIKSAAN HIDUNG", data: pemeriksaanHidung, routeName: "kunjungan.pemeriksaanHidung" },
        { label: "PEMERIKSAAN RAMBUT", data: pemeriksaanRambut, routeName: "kunjungan.pemeriksaanRambut" },
        { label: "PEMERIKSAAN BIBIR", data: pemeriksaanBibir, routeName: "kunjungan.pemeriksaanBibir" },
        { label: "PEMERIKSAAN GIGI GELIGI", data: pemeriksaanGigiGeligi, routeName: "kunjungan.pemeriksaanGigiGeligi" },
        { label: "PEMERIKSAAN LIDAH", data: pemeriksaanLidah, routeName: "kunjungan.pemeriksaanLidah" },
        { label: "PEMERIKSAAN LANGIT-LANGIT", data: pemeriksaanLangitLangit, routeName: "kunjungan.pemeriksaanLangitLangit" },
        { label: "PEMERIKSAAN LEHER", data: pemeriksaanLeher, routeName: "kunjungan.pemeriksaanLeher" },
        { label: "PEMERIKSAAN TENGGOROKAN", data: pemeriksaanTenggorokan, routeName: "kunjungan.pemeriksaanTenggorokan" },
        { label: "PEMERIKSAAN TONSIL", data: pemeriksaanTonsil, routeName: "kunjungan.pemeriksaanTonsil" },
        { label: "PEMERIKSAAN DADA", data: pemeriksaanDada, routeName: "kunjungan.pemeriksaanDada" },
        { label: "PEMERIKSAAN PAYUDARA", data: pemeriksaanPayudara, routeName: "kunjungan.pemeriksaanPayudara" },
        { label: "PEMERIKSAAN PUNGGUNG", data: pemeriksaanPunggung, routeName: "kunjungan.pemeriksaanPunggung" },
        { label: "PEMERIKSAAN PERUT", data: pemeriksaanPerut, routeName: "kunjungan.pemeriksaanPerut" },
        { label: "PEMERIKSAAN GENITAL", data: pemeriksaanGenital, routeName: "kunjungan.pemeriksaanGenital" },
        { label: "PEMERIKSAAN ANUS", data: pemeriksaanAnus, routeName: "kunjungan.pemeriksaanAnus" },
        { label: "PEMERIKSAAN LENGAN ATAS", data: pemeriksaanLenganAtas, routeName: "kunjungan.pemeriksaanLenganAtas" },
        { label: "PEMERIKSAAN LENGAN BAWAH", data: pemeriksaanLenganBawah, routeName: "kunjungan.pemeriksaanLenganBawah" },
        { label: "PEMERIKSAAN JARI TANGAN", data: pemeriksaanJariTangan, routeName: "kunjungan.pemeriksaanJariTangan" },
        { label: "PEMERIKSAAN KUKU TANGAN", data: pemeriksaanKukuTangan, routeName: "kunjungan.pemeriksaanKukuTangan" },
        { label: "PEMERIKSAAN PERSENDIAN TANGAN", data: pemeriksaanPersendianTangan, routeName: "kunjungan.pemeriksaanPersendianTangan" },
        { label: "PEMERIKSAAN TUNGKAI ATAS", data: pemeriksaanTungkaiAtas, routeName: "kunjungan.pemeriksaanTungkaiAtas" },
        { label: "PEMERIKSAAN TUNGKAI BAWAH", data: pemeriksaanTungkaiBawah, routeName: "kunjungan.pemeriksaanTungkaiBawah" },
        { label: "PEMERIKSAAN JARI KAKI", data: pemeriksaanJariKaki, routeName: "kunjungan.pemeriksaanJariKaki" },
        { label: "PEMERIKSAAN KUKU KAKI", data: pemeriksaanKukuKaki, routeName: "kunjungan.pemeriksaanKukuKaki" },
        { label: "PEMERIKSAAN PERSENDIAN KAKI", data: pemeriksaanPersendianKaki, routeName: "kunjungan.pemeriksaanPersendianKaki" },
        { label: "PEMERIKSAAN FARING", data: pemeriksaanFaring, routeName: "kunjungan.pemeriksaanFaring" },
        { label: "PEMERIKSAAN SALURAN CERNAH BAWAH", data: pemeriksaanSaluranCernahBawah, routeName: "kunjungan.pemeriksaanSaluranCernahBawah" },
        { label: "PEMERIKSAAN SALURAN CERNAH ATAS", data: pemeriksaanSaluranCernahAtas, routeName: "kunjungan.pemeriksaanSaluranCernahAtas" },
        { label: "PEMERIKSAAN EEG", data: pemeriksaanEeg, routeName: "kunjungan.pemeriksaanEeg" },
        { label: "PEMERIKSAAN EMG", data: pemeriksaanEmg, routeName: "kunjungan.pemeriksaanEmg" },
        { label: "PEMERIKSAAN RAVEN TEST", data: pemeriksaanRavenTest, routeName: "kunjungan.pemeriksaanRavenTest" },
        { label: "PEMERIKSAAN CAT CLAMS", data: pemeriksaanCatClams, routeName: "kunjungan.pemeriksaanCatClams" },
        { label: "PEMERIKSAAN TRANSFUSI DARAH", data: pemeriksaanTransfusiDarah, routeName: "kunjungan.pemeriksaanTransfusiDarah" },
        { label: "PEMERIKSAAN ASESSMENT M CHAT", data: pemeriksaanAsessmentMChat, routeName: "kunjungan.pemeriksaanAsessmentMChat" },
        { label: "PEMERIKSAAN EKG", data: pemeriksaanEkg, routeName: "kunjungan.pemeriksaanEkg" },
        { label: "PENILAIAN FISIK", data: penilaianFisik, routeName: "kunjungan.penilaianFisik" },
        { label: "PENILAIAN NYERI", data: penilaianNyeri, routeName: "kunjungan.penilaianNyeri" },
        { label: "PENILAIAN STATUS PEDIATRIK", data: penilaianStatusPediatrik, routeName: "kunjungan.penilaianStatusPediatrik" },
        { label: "PENILAIAN DIAGNOSIS", data: penilaianDiagnosis, routeName: "kunjungan.penilaianDiagnosis" },
        { label: "PENILAIAN SKALA MORSE", data: penilaianSkalaMorse, routeName: "kunjungan.penilaianSkalaMorse" },
        { label: "PENILAIAN SKALA HUMPTY DUMPTY", data: penilaianSkalaHumptyDumpty, routeName: "kunjungan.penilaianSkalaHumptyDumpty" },
        { label: "PENILAIAN EPFRA", data: penilaianEpfra, routeName: "kunjungan.penilaianEpfra" },
        { label: "PENILAIAN GET UP GO", data: penilaianGetupGo, routeName: "kunjungan.penilaianGetupGo" },
        { label: "PENILAIAN DEKUBITUS", data: penilaianDekubitus, routeName: "kunjungan.penilaianDekubitus" },
        { label: "PENILAIAN BALLANCE CAIRAN", data: penilaianBallanceCairan, routeName: "kunjungan.penilaianBallanceCairan" },
        { label: "DIAGNOSA", data: diagnosa, routeName: "kunjungan.diagnosa" },
        { label: "RENCANA TERAPI", data: rencanaTerapi, routeName: "kunjungan.rencanaTerapi" },
        { label: "JADWAL KONTROL", data: jadwalKontrol, routeName: "kunjungan.jadwalKontrol" },
        { label: "PERENCANAAN RAWAT INAP", data: perencanaanRawatInap, routeName: "kunjungan.perencanaanRawatInap" },
        { label: "DISCHARGE PLANNING SKRINING", data: dischargePlanningSkrining, routeName: "kunjungan.dischargePlanningSkrining" },
        { label: "DISCHARGE PLANNING FAKTOR RISIKO", data: dischargePlanningFaktorRisiko, routeName: "kunjungan.dischargePlanningFaktorRisiko" },
        { label: "CPPT", data: cppt, routeName: "kunjungan.cppt" },
        { label: "PEMANTUAN HD INTRADIALITIK", data: pemantuanHDIntradialitik, routeName: "kunjungan.pemantuanHDIntradialitik" },
        { label: "TINDAKAN ABC I", data: tindakanAbci, routeName: "kunjungan.tindakanAbci" },
        { label: "TINDAKAN MMPI", data: tindakanMmpi, routeName: "kunjungan.tindakanMmpi" },
    ];

    const validRows = rows.filter(row => row.data && row.data !== "");

    const partSize = validRows.length < 5 ? validRows.length : Math.ceil(validRows.length / 3);
    const firstPart = validRows.slice(0, partSize);
    const secondPart = validRows.slice(partSize, partSize * 2);
    const thirdPart = validRows.slice(partSize * 2);

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
                            <DataLink
                                href={route(row.routeName, { id: row.data })}
                                label={`Data ${row.label}`}
                            />
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
                            <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl text-center">
                                DATA MEDICAL RECORD
                            </h1>
                        </div>
                        <div className="relative flex items-center justify-between pb-7">
                            <h1 className="absolute left-1/2 transform -translate-x-1/2 font-bold italic text-center text-red-400">
                                Hanya menampilkan data yang telah diinput
                            </h1>
                        </div>
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
                            {renderTable(firstPart)}
                            {renderTable(secondPart)}
                            {renderTable(thirdPart)}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}


