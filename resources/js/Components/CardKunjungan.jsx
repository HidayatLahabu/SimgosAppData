import React from "react";
import { formatRibuan } from "@/utils/formatRibuan";

const CardKunjungan = ({ title, value, date }) => {
    const formattedTime = date
        ? new Date(date).toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        })
        : null;

    return (
        <div className="flex items-center p-5 bg-gradient-to-r from-indigo-800 to-indigo-900 rounded-lg shadow-lg text-white space-x-4">
            {/* Bagian Kiri */}
            <div className="flex-1">
                <h2 className="text-lg font-bold text-yellow-500 uppercase">
                    {title}
                </h2>
                {date && (
                    <p className="text-sm text-gray-300 mt-1">
                        Update: {formattedTime}
                    </p>
                )}
            </div>

            {/* Bagian Kanan */}
            <div className="flex items-center justify-center text-6xl font-extrabold text-white">
                {formatRibuan(value)}
            </div>
        </div>
    );
};

export default CardKunjungan;
