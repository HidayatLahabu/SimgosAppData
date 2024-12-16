import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { Link } from '@inertiajs/react';
import ButtonBack from '@/Components/ButtonBack';
import TableDetailRme from './TableDetailRme';

export default function TableRme({
    auth,
    nomorKunjungan,
    nomorPendaftaran,
    namaPasien,
    normPasien,
    ruanganTujuan,
    statusKunjungan,
    tanggalKeluar,
    dpjp,
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
    cppt,
    tandaVital,
    diagnosa,
    jadwalKontrol,
}) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Pendaftaran" />
            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <div className="relative flex items-center justify-between pb-2">
                                    <Link href={route("kunjungan.detail", { id: nomorKunjungan })}>
                                        <ButtonBack href={route("kunjungan.detail", { id: nomorKunjungan })} />
                                    </Link>
                                    <h1 className="absolute left-1/2 transform -translate-x-1/2 uppercase font-bold text-2xl">
                                        DATA DETAIL KUNJUNGAN
                                    </h1>
                                </div>

                                {/* Informasi Detail */}
                                <div className="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-4 gap-2 pb-2 text-sm">
                                    <div className="flex flex-col border p-2 rounded">
                                        Pendaftaran : <br />
                                        <span className="block text-yellow-500">{nomorPendaftaran}</span>
                                    </div>
                                    <div className="flex flex-col border p-2 rounded">
                                        Kunjungan : <br />
                                        <span className="block text-yellow-500">{nomorKunjungan}</span>
                                    </div>
                                    <div className="flex flex-col border p-2 rounded">
                                        NORM : <br />
                                        <span className="block text-yellow-500">{normPasien}</span>
                                    </div>
                                    <div className="flex flex-col border p-2 rounded">
                                        Pasien : <br />
                                        <span className="block text-yellow-500">{namaPasien}</span>
                                    </div>
                                </div>
                                <div className="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-4 gap-2 pb-4 text-sm">
                                    <div className="flex flex-col border p-2 rounded">
                                        Ruangan : <br />
                                        <span className="block text-yellow-500">{ruanganTujuan}</span>
                                    </div>
                                    <div className="flex flex-col border p-2 rounded">
                                        DPJP : <br />
                                        <span className="block text-yellow-500">{dpjp}</span>
                                    </div>
                                    <div className="flex flex-col border p-2 rounded">
                                        Keluar : <br />
                                        <span className="block text-yellow-500">{tanggalKeluar}</span>
                                    </div>
                                    <div className="flex flex-col border p-2 rounded">
                                        Status : <br />
                                        <span className="block text-yellow-500">{statusKunjungan === 0 ? 'Batal' : statusKunjungan === 1 ? 'Sedang Dilayani' : 'Selesai'}</span>
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
                                    cppt={cppt}
                                    tandaVital={tandaVital}
                                    diagnosa={diagnosa}
                                    jadwalKontrol={jadwalKontrol}
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
