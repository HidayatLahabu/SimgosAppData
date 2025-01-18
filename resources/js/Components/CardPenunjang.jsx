import React from "react";
import { UserGroupIcon, HomeIcon, DocumentCheckIcon, ClipboardDocumentCheckIcon, ClipboardDocumentListIcon } from "@heroicons/react/16/solid";

const CardPenunjang = ({
    ruangan,
    jumlahOrder,
    jumlahTindakan,
    catatanHasil,
    jumlahHasil,
    iconColor = "text-white",
    borderColor = "border-gray-700",
    bgGradient = "bg-gradient-to-r from-indigo-800 to-indigo-900",
}) => {

    return (
        <div
            className="flex flex-col px-5 py-5 bg-gradient-to-r from-indigo-800 to-indigo-900 text-center rounded-lg shadow-lg hover:shadow-xl transform hover:from-indigo-700 hover:to-indigo-800 transition-transform duration-300 border border-gray-300 dark:border-gray-700 group"
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
                        Order : {jumlahOrder}
                    </p>
                </div>
                <div className="flex items-center">
                    <DocumentCheckIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                    <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                        Tindakan : {jumlahTindakan}
                    </p>
                </div>
            </div>

            <div className="flex justify-between items-center mb-2 text-left">
                <div className="flex items-center">
                    <ClipboardDocumentListIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                    <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                        Hasil : {jumlahHasil}
                    </p>
                </div>
                <div className="flex items-center">
                    <ClipboardDocumentCheckIcon className={`w-5 h-5 mr-2 ${iconColor}`} />
                    <p className="text-sm font-semibold text-gray-300 group-hover:text-gray-100">
                        Catatan : {catatanHasil}
                    </p>
                </div>
            </div>
        </div>
    );
};

export default CardPenunjang;
