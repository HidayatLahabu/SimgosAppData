import React from "react";
import { formatRibuan } from "@/utils/formatRibuan";

const CardCombined = ({
    title,
    yearValue,
    lastYearValue,
    monthValue,
    lastMonthValue,
    valueSize = "text-2xl",
}) => {
    const yearPercentageChange = ((yearValue - lastYearValue) / lastYearValue) * 100;
    const monthPercentageChange = ((monthValue - lastMonthValue) / lastMonthValue) * 100;

    const formattedYearPercentageChange = yearPercentageChange.toFixed(2);
    const formattedMonthPercentageChange = monthPercentageChange.toFixed(2);

    return (
        <div
            className="flex-1 px-5 py-4 bg-gradient-to-r from-indigo-800 to-indigo-900 text-center rounded-lg shadow-lg hover:shadow-xl transform hover:from-indigo-700 hover:to-indigo-800 transition-transform duration-300 border border-gray-300 dark:border-gray-700 group"
        >
            <h2 className="text-2xl font-bold text-gray-200 dark:text-yellow-500 group-hover:text-gray-200 mb-4">
                {title}
            </h2>
            <div className="flex justify-between items-start space-x-16">
                {/* Section for Year Data */}
                <div className="text-left w-1/2">
                    <p className="text-lg font-semibold text-white">
                        Tahun Ini
                    </p>
                    <p className={`${valueSize} font-semibold text-white group-hover:text-yellow-400`}>
                        {formatRibuan(yearValue)}
                    </p>
                    <p className="text-sm font-medium text-gray-400 mt-2 group-hover:text-yellow-500">
                        Tahun Lalu: {formatRibuan(lastYearValue)}
                    </p>
                    <p
                        className={`text-sm font-medium mt-2 ${formattedYearPercentageChange > 0 ? "text-green-500" : "text-red-500"
                            }`}
                    >
                        {formattedYearPercentageChange > 0
                            ? `+${formattedYearPercentageChange}%`
                            : `${formattedYearPercentageChange}%`}
                    </p>
                </div>

                {/* Section for Month Data */}
                <div className="text-left w-1/2">
                    <p className="text-lg font-semibold text-cyan-200">
                        Bulan Ini
                    </p>
                    <p className={`${valueSize} font-semibold text-cyan-200 group-hover:text-yellow-400`}>
                        {formatRibuan(monthValue)}
                    </p>
                    <p className="text-sm font-medium text-gray-400 mt-2 group-hover:text-yellow-500">
                        Bulan Lalu: {formatRibuan(lastMonthValue)}
                    </p>
                    <p
                        className={`text-sm font-medium mt-2 ${formattedMonthPercentageChange > 0 ? "text-green-500" : "text-red-500"
                            }`}
                    >
                        {formattedMonthPercentageChange > 0
                            ? `+${formattedMonthPercentageChange}%`
                            : `${formattedMonthPercentageChange}%`}
                    </p>
                </div>
            </div>
        </div>
    );
};

export default CardCombined;
