import React from "react";
import { formatRibuan } from "@/utils/formatRibuan";

const CardMonth = ({
    title,
    value,
    valueSize = "text-2xl",
    lastValue
}) => {
    const percentageChange = ((value - lastValue) / lastValue) * 100;
    const formattedPercentageChange = percentageChange.toFixed(2);

    return (
        <div
            className="flex-1 px-5 py-4 bg-gradient-to-r from-indigo-800 to-indigo-900 text-center rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 hover:from-indigo-700 hover:to-indigo-800  transition-transform duration-300 border border-gray-300 dark:border-gray-700 group"
        >
            <div className="flex justify-between items-center">
                <div className="text-left">
                    <h2 className="text-2xl font-bold text-gray-200 dark:text-yellow-500 uppercase group-hover:text-gray-200">
                        {title}
                    </h2>
                    <p className="text-sm font-medium text-gray-400 mt-2 group-hover:text-yellow-500">
                        Bulan Lalu: {formatRibuan(lastValue)}
                    </p>
                </div>
                <div className="text-right">
                    <p className={`${valueSize} font-semibold text-white group-hover:text-yellow-400`}>
                        {formatRibuan(value)}
                    </p>
                    <p className={`text-sm font-medium mt-2 ${formattedPercentageChange > 0 ? 'text-green-500' : 'text-red-500'}`}>
                        {formattedPercentageChange > 0 ? `+${formattedPercentageChange}%` : `${formattedPercentageChange}%`}
                    </p>

                </div>
            </div>
        </div>
    );
};

export default CardMonth;

