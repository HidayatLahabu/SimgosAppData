import React from "react";
import { formatRibuan } from "@/utils/formatRibuan";
import { UserIcon } from '@heroicons/react/16/solid';

const Card = ({
    href,
    title,
    value,
    titleSize = "text-lg",
    valueSize = "text-2xl",
    description = "PASIEN",
    icon: Icon = UserIcon,
    iconColor = "text-white"
}) => {
    return (
        <a
            href={href}
            className="flex-1 px-5 py-4 bg-gradient-to-r from-indigo-800 to-indigo-900 hover:from-indigo-700 hover:to-indigo-800 text-center rounded-lg shadow-lg hover:shadow-xl transform transition-transform duration-300 border border-gray-300 dark:border-gray-700 group flex flex-col items-center"
        >

            {Icon && (
                <div className={`mb-2 group-hover:text-yellow-400 ${iconColor}`}>
                    <Icon className="w-8 h-8" />
                </div>
            )}

            <h2 className={`${titleSize} font-bold text-gray-200 dark:text-yellow-500 uppercase group-hover:text-gray-200`}>
                {title}
            </h2>
            <p className={`${valueSize} font-semibold text-white mt-2 group-hover:text-red-500`}>
                {formatRibuan(value)} {description}
            </p>
        </a>
    );
};

export default Card;
