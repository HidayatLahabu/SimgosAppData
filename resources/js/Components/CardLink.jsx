import React from "react";
import { formatRibuan } from "@/utils/formatRibuan";

const CardLink = ({ href, title, value, valueColor = "text-indigo-600", description = "PASIEN" }) => {
    return (
        <a
            href={href}
            className="flex-1 p-4 bg-white dark:bg-indigo-800 text-center rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 block"
        >
            <h2 className="text-lg font-bold text-gray-700 dark:text-yellow-400">{title}</h2>
            <p className={`text-2xl font-semibold ${valueColor} dark:text-white mt-2`}>
                {formatRibuan(value)} {description}
            </p>
            <span className="text-xs text-gray-700 dark:text-gray-400 mt-1 block">
                Click untuk detail
            </span>
        </a>
    );
};

export default CardLink;