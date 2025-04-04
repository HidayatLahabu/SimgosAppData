import React from "react";
import CardWaktuTunggu from "@/Components/Card/CardWaktuTunggu";
import { UsersIcon } from "@heroicons/react/16/solid";

const convertSecondsToHMS = (seconds) => {
    const totalSeconds = Math.floor(seconds);
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const remainingSeconds = totalSeconds % 60;

    // Menambahkan leading zero jika jam, menit, atau detik kurang dari 10
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
};


export default function WaktuTunggu({ waktuTungguTercepat, waktuTungguTerlama }) {

    // const waktuTungguMenitCepat = convertSecondsToHMS(waktuTungguTercepat.WAKTU_TUNGGU_RATA_RATA);
    // const waktuTungguMenitLama = convertSecondsToHMS(waktuTungguTerlama.WAKTU_TUNGGU_RATA_RATA);

    if (!waktuTungguTercepat || !waktuTungguTerlama) {
        return <div className="text-gray-500">Data waktu tunggu belum tersedia.</div>;
    }

    const waktuTungguMenitCepat = waktuTungguTercepat?.WAKTU_TUNGGU_RATA_RATA != null
        ? convertSecondsToHMS(waktuTungguTercepat.WAKTU_TUNGGU_RATA_RATA)
        : "00:00:00";

    const waktuTungguMenitLama = waktuTungguTerlama?.WAKTU_TUNGGU_RATA_RATA != null
        ? convertSecondsToHMS(waktuTungguTerlama.WAKTU_TUNGGU_RATA_RATA)
        : "00:00:00";

    return (
        <div className="max-w-full mx-auto sm:pr-5 lg:pr-5 w-full">
            <div className="text-gray-900 dark:text-gray-100 w-full">
                <div className="grid grid-cols-1 gap-2">
                    <CardWaktuTunggu
                        ruangan={waktuTungguTercepat.UNITPELAYANAN}
                        dpjp={waktuTungguTercepat.DOKTER_REG}
                        jumlahPasien={waktuTungguTercepat.JUMLAH_PASIEN}
                        waktuTunggu={waktuTungguMenitCepat}
                        waktuStatus="Tercepat"
                        waktuColor="text-gray-100"
                        iconColor="text-green-500"
                        keterangan='Rata-rata Waktu Tunggu Tercepat Bulan Ini'
                    />

                    <CardWaktuTunggu
                        ruangan={waktuTungguTerlama.UNITPELAYANAN}
                        dpjp={waktuTungguTerlama.DOKTER_REG}
                        jumlahPasien={waktuTungguTerlama.JUMLAH_PASIEN}
                        waktuTunggu={waktuTungguMenitLama}
                        waktuStatus="normal"
                        waktuColor="text-gray-100"
                        iconColor="text-red-500"
                        icon={UsersIcon}
                        keterangan='Rata-rata Waktu Tunggu Terlama Bulan Ini'
                    />
                </div>
            </div>
        </div>
    );
}
