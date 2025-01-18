import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import TodayData from './Dashboard/TodayData';
import StatisticTahun from './Dashboard/StatisticTahun';
import KunjunganHarian from './Dashboard/KunjunganHarian';
import WaktuTunggu from './Dashboard/WaktuTunggu';
import LayananPenunjang from './Dashboard/LayananPenunjang';
import RanapKontrolWrapper from './Dashboard/RanapKontrolWrapper';

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
    waktuTungguTercepat,
    waktuTungguTerlama,
    dataLaboratorium,
    hasilLaboratorium,
    catatanLaboratorium,
    dataRadiologi,
    hasilRadiologi,
    catatanRadiologi,
    dataFarmasi,
    orderFarmasi,
    telaahFarmasi,
    kunjunganRanap,
    rekonBpjs,
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

            <div className="max-w-full mx-auto sm:px-5 lg:px-5 py-3 dark:bg-indigo-900 rounded">
                <h1 className="uppercase text-center font-extrabold text-lg text-indigo-700 dark:text-yellow-400">
                    {formattedDate}
                </h1>
            </div>

            <div className="pt-5 pb-2 flex w-full gap-2">
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

                <div className="flex flex-col w-1/4">
                    <WaktuTunggu
                        waktuTungguTercepat={waktuTungguTercepat}
                        waktuTungguTerlama={waktuTungguTerlama}
                    />
                </div>
            </div>

            <div className="pb-5 flex flex-wrap w-full">
                <KunjunganHarian statistikKunjungan={statistikKunjungan} />
            </div>

            <div className="flex flex-row gap-2 justify-center items-start w-full pt-1">
                <div className="flex flex-col w-1/4">
                    <LayananPenunjang
                        dataLaboratorium={dataLaboratorium}
                        hasilLaboratorium={hasilLaboratorium}
                        catatanLaboratorium={catatanLaboratorium}
                        dataRadiologi={dataRadiologi}
                        hasilRadiologi={hasilRadiologi}
                        catatanRadiologi={catatanRadiologi}
                        dataFarmasi={dataFarmasi}
                        orderFarmasi={orderFarmasi}
                        telaahFarmasi={telaahFarmasi}
                    />
                </div>
                <div className="flex flex-col w-3/4 -mx-1 pr-5">
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

            <RanapKontrolWrapper
                kunjunganRanap={kunjunganRanap}
                rekonBpjs={rekonBpjs}
            />

        </AuthenticatedLayout>
    );
}
