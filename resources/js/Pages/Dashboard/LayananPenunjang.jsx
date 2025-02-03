import React from "react";
import CardPenunjang from "@/Components/Card/CardPenunjang";
import CardFarmasi from "@/Components/Card/CardFarmasi";
import { formatRibuan } from "@/utils/formatRibuan";

export default function LayananPenunjang({
    dataLaboratorium,
    hasilLaboratorium,
    catatanLaboratorium,
    dataRadiologi,
    hasilRadiologi,
    catatanRadiologi,
    dataFarmasi,
    orderFarmasi,
    telaahFarmasi,
}) {

    return (
        <div className="max-w-full mx-auto sm:pl-5 lg:pl-5 w-full">
            <div className="text-gray-900 dark:text-gray-100 w-full">
                <div className="grid grid-cols-1 gap-3">
                    <CardPenunjang
                        ruangan="LABORATORIUM"
                        jumlahOrder={formatRibuan(dataLaboratorium.orderLab)}
                        jumlahTindakan={formatRibuan(dataLaboratorium.tindakanLab)}
                        jumlahHasil={formatRibuan(hasilLaboratorium.hasilLab)}
                        catatanHasil={formatRibuan(catatanLaboratorium.catatanLab)}
                        iconColor="text-orange-500"
                    />

                    <CardPenunjang
                        ruangan="RADIOLOGI"
                        jumlahOrder={formatRibuan(dataRadiologi.orderRad)}
                        jumlahTindakan={formatRibuan(dataRadiologi.tindakanRad)}
                        jumlahHasil={formatRibuan(hasilRadiologi.hasilRad)}
                        catatanHasil={formatRibuan(catatanRadiologi.catatanRad)}
                        iconColor="text-pink-500"
                    />

                    <CardFarmasi
                        ruangan="FARMASI"
                        kunjungan={formatRibuan(dataFarmasi.jumlahKunjungan)}
                        order={formatRibuan(orderFarmasi.jumlahOrder)}
                        detail={formatRibuan(orderFarmasi.jumlahDetail)}
                        telaah={formatRibuan(telaahFarmasi.jumlahTelaah)}
                        iconColor="text-amber-500"
                    />
                </div>
            </div>
        </div>
    );
}
