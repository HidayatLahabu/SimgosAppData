import React from "react";
import { formatRibuan } from "@/utils/formatRibuan";
import { UserGroupIcon, HomeIcon, DocumentCheckIcon, ClipboardDocumentCheckIcon, ClipboardDocumentListIcon } from "@heroicons/react/16/solid";

const CardPenunjang = ({
    ruangan,
    kunjunganTotal,
    kunjunganStatus,
    orderStatus,
    hasilStatus,
    iconColor = "text-white",
    borderColor = "border-gray-700",
    bgGradient = "bg-gradient-to-r from-indigo-800 to-indigo-900",
}) => {

    return (
        <div
            className={`flex flex-col px-5 py-5 bg-gradient-to-r ${bgGradient} rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-transform duration-300 border ${borderColor} group`}
        >
            <div className="flex items-center mb-2 pt-1 text-left">
                <HomeIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                <h2 className="text-sm uppercase font-bold text-gray-200 group-hover:text-yellow-500">
                    {ruangan}
                </h2>
            </div>

            <div className="flex items-center mb-3 text-left">
                <UserGroupIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                    Kunjungan Diterima: {kunjunganStatus}
                </p>
            </div>

            <div className="flex items-center mb-3 text-left">
                <DocumentCheckIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                    Jumlah Order: {kunjunganTotal}
                </p>
            </div>

            <div className="flex items-center mb-3 text-left">
                <ClipboardDocumentListIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                    Order Final : {orderStatus}
                </p>
            </div>

            <div className="flex items-center mb-2 text-left">
                <ClipboardDocumentCheckIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                    Final Hasil: {hasilStatus}
                </p>
            </div>
        </div>
    );
};

export default CardPenunjang;
