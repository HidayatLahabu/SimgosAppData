import React, { useEffect } from "react";
import { useForm } from "@inertiajs/react";
import InputLabel from "@/Components/Input/InputLabel";
import TextInput from "@/Components/Input/TextInput";
import SelectTwoInput from "@/Components/Select/SelectTwoInput";

export default function Cetak({
    ruangan,
    caraBayar,
}) {

    const { data, setData } = useForm({
        dari_tanggal: '',
        sampai_tanggal: ''
    });

    useEffect(() => {
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const firstDayOfYear = `${currentYear}-01-01`;
        const formattedCurrentDate = currentDate.toISOString().split("T")[0];

        setData(prevData => ({
            ...prevData,
            dari_tanggal: firstDayOfYear,
            sampai_tanggal: formattedCurrentDate
        }));
    }, []);

    const onRuanganChange = (selectedOption) => {
        if (selectedOption && selectedOption.value) {
            setData(prevData => ({ ...prevData, ruangan: selectedOption.value }));
        } else {
            setData(prevData => ({ ...prevData, ruangan: '' }));  // Atau handling sesuai kebutuhan
        }
    };

    const onBayarChange = (selectedOption) => {
        if (selectedOption && selectedOption.value) {
            setData(prevData => ({ ...prevData, caraBayar: selectedOption.value }));
        } else {
            setData(prevData => ({ ...prevData, caraBayar: '' }));  // Atau handling sesuai kebutuhan
        }
    };

    const onSubmit = (e) => {
        e.preventDefault();

        // Filter out empty values
        const filteredData = Object.fromEntries(Object.entries(data).filter(([_, v]) => v !== ''));

        const queryString = new URLSearchParams(filteredData).toString();
        window.open(route("kegiatanPasienMeninggal.print") + "?" + queryString, "_blank");
    };

    return (
        <div className="pt-2">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5 pb-2">
                <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg">
                    <form
                        onSubmit={onSubmit}
                        className="p-4 sm-8 bg-white dark:bg-indigo-950 shadow sm:rounded-lg"
                    >
                        <h1 className="uppercase text-center font-bold text-2xl pt-2 text-white">
                            Cetak Laporan Kegiatan Pasien Meninggal
                        </h1>

                        <div className="mt-4 flex space-x-4">
                            <div className="flex-1">
                                <InputLabel htmlFor="ruangan" value="Ruangan Tujuan" />
                                <SelectTwoInput
                                    id="ruangan"
                                    name="ruangan"
                                    className="mt-1 block w-full"
                                    placeholder="Pilih Ruangan"
                                    options={Array.isArray(ruangan) ?
                                        ruangan.map((item) => ({
                                            value: item.ID,
                                            label: item.DESKRIPSI,
                                        })) : []}
                                    onChange={onRuanganChange}
                                />
                            </div>
                            <div className="flex-1">
                                <InputLabel htmlFor="caraBayar" value="Cara Bayar" />
                                <SelectTwoInput
                                    id="caraBayar"
                                    name="caraBayar"
                                    className="mt-1 block w-full"
                                    placeholder="Pilih Cara Bayar"
                                    options={Array.isArray(caraBayar) ?
                                        caraBayar.map((item) => ({
                                            value: item.ID,
                                            label: item.DESKRIPSI,
                                        })) : []}
                                    onChange={onBayarChange}
                                />
                            </div>
                        </div>

                        <div className="mt-4 flex space-x-4">
                            <div className="flex-1">
                                <InputLabel
                                    htmlFor="dari_tanggal"
                                    value="Dari Tanggal"
                                />
                                <TextInput
                                    type="date"
                                    id="dari_tanggal"
                                    name="dari_tanggal"
                                    className="mt-1 block w-full"
                                    value={data.dari_tanggal}
                                    onChange={(e) => setData(prevData => ({ ...prevData, dari_tanggal: e.target.value }))}
                                />
                            </div>

                            <div className="flex-1">
                                <InputLabel
                                    htmlFor="sampai_tanggal"
                                    value="Sampai Tanggal"
                                />
                                <TextInput
                                    type="date"
                                    id="sampai_tanggal"
                                    name="sampai_tanggal"
                                    className="mt-1 block w-full"
                                    value={data.sampai_tanggal}
                                    onChange={(e) => setData(prevData => ({ ...prevData, sampai_tanggal: e.target.value }))}
                                />
                            </div>
                        </div>

                        <div className="flex justify-between items-center mt-4">
                            <button
                                className="bg-red-500 py-1 px-3 text-gray-200 rounded shadow transition-all hover:bg-red-700 ml-auto"
                                type="submit"
                            >
                                Cetak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    )
}

