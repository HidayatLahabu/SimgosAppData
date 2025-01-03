import React from "react";
import { formatRibuan } from "@/utils/formatRibuan";

const CardDashboard = ({ title, value, titleSize = "text-lg", valueSize = "text-2xl" }) => {
    return (
        <div
            className="flex-1 px-5 py-4 bg-gradient-to-r from-indigo-800 to-indigo-900 text-center rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 hover:from-indigo-700 hover:to-indigo-800  transition-transform duration-300 border border-gray-300 dark:border-gray-700 group"
        >
            <h2 className={`${titleSize} font-bold text-gray-200 dark:text-yellow-500 uppercase group-hover:text-gray-200`}>
                {title}
            </h2>
            <p className={`${valueSize} font-semibold text-white mt-2 group-hover:text-red-500`}>
                {formatRibuan(value)}
            </p>
        </div>
    );
};

export default CardDashboard;
