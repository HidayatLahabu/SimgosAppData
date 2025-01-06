import React from "react";
import { formatRibuan } from "@/utils/formatRibuan";

const CardKunjungan = ({ title, todayValue, date, yesterdayValue, yesterdayDate }) => {

    const formattedTime = date
        ? date.split(' ')[1]
        : null;

    return (
        <div className="flex items-center p-5 bg-gradient-to-r from-indigo-800 to-indigo-900 rounded-lg shadow-lg text-white space-x-4 hover:bg-gradient-to-r hover:from-indigo-700 hover:to-indigo-800 hover:shadow-xl transform hover:scale-105 transition-transform duration-300 group">
            {/* Bagian Kiri */}
            <div className="flex-1">
                <h2 className="text-lg font-bold text-yellow-500 uppercase group-hover:text-white transition-colors duration-300">
                    {title}
                </h2>
                {date && todayValue !== 0 ? (
                    <p className="text-xs text-gray-300 mt-1 group-hover:text-yellow-500 transition-colors duration-300">
                        Update: {formattedTime}
                    </p>
                ) : (
                    <p className="text-xs text-gray-300 mt-1 group-hover:text-red-500 transition-colors duration-300">
                        Belum Terupdate
                    </p>
                )}
                {/* <p className="text-xl text-red-400 mt-1 group-hover:text-gray-200 transition-colors duration-300">
                    {yesterdayDate}: {yesterdayValue}
                </p> */}
                <p className="text-sm text-red-400 mt-1 group-hover:text-gray-200 transition-colors duration-300">
                    Sebelumnya: {yesterdayValue}
                </p>
            </div>

            {/* Bagian Kanan */}
            <div className="flex items-center justify-center text-5xl font-extrabold text-green-500 group-hover:text-yellow-500 transition-colors duration-300">
                {formatRibuan(todayValue)}
            </div>
        </div>

    );
};

export default CardKunjungan;
