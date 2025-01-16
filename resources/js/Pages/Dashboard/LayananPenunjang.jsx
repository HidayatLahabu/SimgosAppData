import React from "react";
import CardPenunjang from "@/Components/CardPenunjang";
import { formatRibuan } from "@/utils/formatRibuan";


export default function LayananPenunjang({ dataLaboratorium, dataRadiologi }) {

    return (
        <div className="max-w-full mx-auto sm:pl-5 lg:pl-5 w-full">
            <div className="text-gray-900 dark:text-gray-100 w-full">
                <div className="grid grid-cols-1 gap-2">
                    <CardPenunjang
                        ruangan="LABORATORIUM"
                        kunjunganStatus={formatRibuan(dataLaboratorium.kunjunganStatus)}
                        kunjunganTotal={formatRibuan(dataLaboratorium.kunjunganTotal)}
                        orderStatus={formatRibuan(dataLaboratorium.orderStatus)}
                        hasilStatus={formatRibuan(dataLaboratorium.hasilStatus)}
                        iconColor="text-orange-500"
                    />

                    <CardPenunjang
                        ruangan="RADIOLOGI"
                        kunjunganTotal={formatRibuan(dataRadiologi.kunjunganTotal)}
                        kunjunganStatus={formatRibuan(dataRadiologi.kunjunganStatus)}
                        orderStatus={formatRibuan(dataRadiologi.orderStatus)}
                        hasilStatus={formatRibuan(dataRadiologi.hasilStatus)}
                        iconColor="text-pink-500"
                    />
                </div>
            </div>
        </div>
    );
}
