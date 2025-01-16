import React from "react";
import { formatRibuan } from "@/utils/formatRibuan"; // Pastikan fungsi ini tersedia
import { UsersIcon, HomeModernIcon, ClockIcon } from "@heroicons/react/16/solid";

const CardWaktuTunggu = ({
    href,
    ruangan,
    dpjp,
    jumlahPasien,
    waktuTunggu,
    waktuStatus = "normal",
    waktuColor = "text-white",
    iconColor = "text-white",
    borderColor = "border-gray-700",
    bgGradient = "bg-gradient-to-r from-indigo-800 to-indigo-900",
}) => {
    const waktuStatusClass =
        waktuStatus === "tercepat"
            ? "group-hover:text-green-400"
            : waktuStatus === "terlambat"
                ? "group-hover:text-red-500"
                : "group-hover:text-yellow-400";

    return (
        <a
            href={href}
            className={`flex flex-col px-5 py-5 bg-gradient-to-r ${bgGradient} rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-transform duration-300 border ${borderColor} group`}
        >
            {/* Ruangan */}
            <div className="flex items-center mb-5 text-left">
                <HomeModernIcon className={`w-5 h-5 mr-1 ${iconColor}`} />
                <h2 className="text-sm uppercase font-bold text-gray-200 group-hover:text-yellow-500">
                    {ruangan}
                </h2>
            </div>

            {/* DPJP */}
            <div className="flex items-center mb-5 text-left">
                <UsersIcon className={`w-5 h-5 mr-1 ${iconColor}`} />
                <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                    {dpjp}
                </p>
            </div>

            {/* Jumlah Pasien dan Waktu Tunggu */}
            <div className="flex justify-between items-center w-full">
                {/* Pasien */}
                <div className="flex items-center mb-3">
                    <UsersIcon className={`w-5 h-5 mr-1 ${iconColor}`} />
                    <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                        Pasien: {formatRibuan(jumlahPasien)}
                    </p>
                </div>

                {/* Waktu Tunggu */}
                <div className="flex items-center mb-3">
                    <ClockIcon className={`w-5 h-5 mr-1 ${iconColor}`} />
                    <p
                        className={`text-sm font-bold ${waktuColor} ${waktuStatusClass}`}
                    >
                        {waktuTunggu} menit
                    </p>
                </div>
            </div>
        </a>
    );
};

export default CardWaktuTunggu;
