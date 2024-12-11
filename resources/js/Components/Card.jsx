import React from "react";
import { formatRibuan } from "@/utils/formatRibuan";

const Card = ({ title, value, titleSize = "text-lg", valueSize = "text-2xl" }) => {
    return (
        <div className="flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700">
            <h2 className={`${titleSize} font-bold text-gray-700 dark:text-yellow-400 uppercase`}>{title}</h2>
            <p className={`${valueSize} font-semibold text-indigo-600 dark:text-white mt-2`}>
                {formatRibuan(value)} Pasien
            </p>
        </div>
    );
};

export default Card;
