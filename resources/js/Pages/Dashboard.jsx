import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import TodayData from './Dashboard/TodayData';
import StatisticTahun from './Dashboard/StatisticTahun';
import StatisticBulan from './Dashboard/StatisticBulan';
import KunjunganHarian from './Dashboard/KunjunganHarian';
import RajalBulanan from './Dashboard/RajalBulanan'
import DaruratBulanan from './Dashboard/DaruratBulanan'
import RanapBulanan from './Dashboard/RanapBulanan'
import LaboratoriumBulanan from './Dashboard/LaboratoriumBulanan';
import RadiologiBulanan from './Dashboard/RadiologiBulanan';
import WaktuTunggu from './Dashboard/WaktuTunggu';
import LayananPenunjang from './Dashboard/LayananPenunjang';

export default function Dashboard({
    auth,
    pendaftaran,
    kunjungan,
    konsul,
    mutasi,
    kunjunganBpjs,
    rencanaKontrol,
    laboratorium,
    radiologi,
    resep,
    pulang,
    statistikKunjungan,
    statistikTahunIni,
    statistikTahunLalu,
    statistikBulanIni,
    statistikBulanLalu,
    rawatJalanBulanan,
    rawatDaruratBulanan,
    rawatInapBulanan,
    laboratoriumBulanan,
    radiologiBulanan,
    waktuTungguTercepat,
    waktuTungguTerlama,
    dataLaboratorium,
    hasilLaboratorium,
    catatanLaboratorium,
    dataRadiologi,
    hasilRadiologi,
    catatanRadiologi,
}) {

    const today = new Date();

    const formattedDate = today.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }) + ', JAM ' + today.toLocaleTimeString('id-ID', {
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hour12: false,
    }).replace(/\./g, ':');

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Beranda" />

            <div className="max-w-full mx-auto sm:px-5 lg:px- w-full pt-3">
                <h1 className="uppercase text-center font-extrabold text-lg text-indigo-700 dark:text-yellow-400">
                    {formattedDate}
                </h1>
            </div>

            <div className="pt-3 pb-2 flex w-full gap-2">
                <div className="flex-1">
                    <TodayData
                        auth={auth}
                        pendaftaran={pendaftaran}
                        kunjungan={kunjungan}
                        konsul={konsul}
                        mutasi={mutasi}
                        kunjunganBpjs={kunjunganBpjs}
                        laboratorium={laboratorium}
                        radiologi={radiologi}
                        resep={resep}
                        pulang={pulang}
                        rencanaKontrol={rencanaKontrol}
                    />
                </div>

                <div className="w-1/4">
                    <WaktuTunggu
                        waktuTungguTercepat={waktuTungguTercepat}
                        waktuTungguTerlama={waktuTungguTerlama}
                    />
                </div>
            </div>

            <div className="pb-2 flex flex-wrap w-full">
                <KunjunganHarian statistikKunjungan={statistikKunjungan} />
            </div>

            <div className="flex flex-row gap-2 justify-center items-start w-full">
                <div className="flex flex-col w-1/4">
                    <h1 className="pt-3 uppercase text-center font-extrabold text-2xl dark:text-yellow-400">
                        Layanan Penunjang
                    </h1>
                    <LayananPenunjang
                        dataLaboratorium={dataLaboratorium}
                        hasilLaboratorium={hasilLaboratorium}
                        catatanLaboratorium={catatanLaboratorium}
                        dataRadiologi={dataRadiologi}
                        hasilRadiologi={hasilRadiologi}
                        catatanRadiologi={catatanRadiologi}
                    />
                </div>
                <div className="flex flex-col w-3/4 -mx-1 pr-5">
                    <h1 className="pt-3 uppercase text-center font-extrabold text-2xl dark:text-yellow-400">
                        Indikator Pelayanan
                    </h1>
                    <div className="pb-2 flex flex-wrap w-full">
                        <StatisticTahun
                            statistikTahunIni={statistikTahunIni}
                            statistikTahunLalu={statistikTahunLalu}
                            statistikBulanIni={statistikBulanIni}
                            statistikBulanLalu={statistikBulanLalu}
                        />
                    </div>
                </div>

            </div>

            <h1 className="uppercase text-center font-extrabold text-2xl text-indigo-700 dark:text-yellow-400">
                Kunjungan Bulanan
            </h1>
            <div className="pb-5 flex flex-wrap w-full">
                <div className="w-1/5">
                    <RajalBulanan rawatJalanBulanan={rawatJalanBulanan} />
                </div>
                <div className="w-1/5">
                    <DaruratBulanan rawatDaruratBulanan={rawatDaruratBulanan} />
                </div>
                <div className="w-1/5">
                    <RanapBulanan rawatInapBulanan={rawatInapBulanan} />
                </div>
                <div className="w-1/5">
                    <LaboratoriumBulanan laboratoriumBulanan={laboratoriumBulanan} />
                </div>
                <div className="w-1/5">
                    <RadiologiBulanan radiologiBulanan={radiologiBulanan} />
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
