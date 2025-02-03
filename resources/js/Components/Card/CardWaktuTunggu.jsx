import React from "react";
import { formatRibuan } from "@/utils/formatRibuan"; // Pastikan fungsi ini tersedia
import { UserIcon, UsersIcon, HomeIcon, ClockIcon } from "@heroicons/react/16/solid";

const CardWaktuTunggu = ({
    href,
    ruangan,
    dpjp,
    jumlahPasien,
    waktuTunggu,
    waktuStatus = "normal",
    waktuColor = "text-white",
    iconColor = "text-white",
    keterangan = 'Waktu tunggu rata-rata poliklinik bulan berjalan',
}) => {
    const waktuStatusClass =
        waktuStatus === "tercepat"
            ? "group-hover:text-green-400"
            : waktuStatus === "terlambat"
                ? "group-hover:text-red-500"
                : "group-hover:text-yellow-400";

    return (
        <div
            className="flex-1 px-5 py-4 bg-gradient-to-r from-indigo-800 to-indigo-900 text-center rounded-lg shadow-lg hover:shadow-xl transform hover:from-indigo-700 hover:to-indigo-800 transition-transform duration-300 border border-gray-300 dark:border-gray-700 group"
        >
            <div className="flex items-center mb-2 text-left">
                <HomeIcon className={`w-5 h-5 mr-1 ${iconColor}`} />
                <h2 className="text-sm uppercase font-bold text-gray-200 group-hover:text-yellow-500">
                    {ruangan}
                </h2>
            </div>

            <div className="flex items-center mb-2 text-left">
                <UserIcon className={`w-5 h-5 mr-1 ${iconColor}`} />
                <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                    {dpjp}
                </p>
            </div>

            <div className="flex justify-between items-center w-full">
                {/* Pasien */}
                <div className="flex items-center mb-1">
                    <UsersIcon className={`w-5 h-5 mr-1 ${iconColor}`} />
                    <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                        Pasien: {formatRibuan(jumlahPasien)}
                    </p>
                </div>

                <div className="flex items-center mb-2">
                    <ClockIcon className={`w-5 h-5 mr-1 ${iconColor}`} />
                    <p
                        className={`text-sm font-bold ${waktuColor} ${waktuStatusClass}`}
                    >
                        {waktuTunggu}
                    </p>
                </div>
            </div>
            <p className="text-xs text-yellow-400 text-center -mb-1">{keterangan}</p>
        </div>
    );
};

export default CardWaktuTunggu;
