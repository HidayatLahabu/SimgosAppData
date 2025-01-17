import React from "react";
import { UserGroupIcon, HomeIcon, DocumentCheckIcon, ClipboardDocumentCheckIcon, ClipboardDocumentListIcon } from "@heroicons/react/16/solid";

const CardFarmasi = ({
    ruangan,
    kunjungan,
    order,
    detail,
    telaah,
    iconColor = "text-white",
    borderColor = "border-gray-700",
    bgGradient = "bg-gradient-to-r from-indigo-800 to-indigo-900",
}) => {

    return (
        <div
            className={`flex flex-col px-5 py-5 bg-gradient-to-r ${bgGradient} rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-transform duration-300 border ${borderColor} group`}
        >
            <div className="flex items-center mb-2 text-left">
                <HomeIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                <h2 className="text-sm uppercase font-bold text-gray-200 group-hover:text-yellow-500">
                    {ruangan}
                </h2>
            </div>

            <div className="flex justify-between items-center mb-2 text-left">
                <div className="flex items-center">
                    <UserGroupIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                    <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                        Kunjungan : {kunjungan}
                    </p>
                </div>
                <div className="flex items-center">
                    <DocumentCheckIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                    <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                        Order : {order}
                    </p>
                </div>
            </div>

            <div className="flex justify-between items-center mb-2 text-left">
                <div className="flex items-center">
                    <ClipboardDocumentListIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                    <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                        Detail : {detail}
                    </p>
                </div>
                <div className="flex items-center">
                    <ClipboardDocumentCheckIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                    <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                        Telaah : {telaah}
                    </p>
                </div>
            </div>
        </div>
    );
};

export default CardFarmasi;

