import React, { useEffect } from "react";
import { useForm } from "@inertiajs/react";
import SelectTwoInput from "@/Components/Select/SelectTwoInput";
import InputLabel from "@/Components/Input/InputLabel";
import TextInput from "@/Components/Input/TextInput";

export default function Cetak({ keadaanPulang = [] }) {

    const { data, setData } = useForm({
        dari_tanggal: '',
        sampai_tanggal: '',
        keadaanPulang: "",
    });

    useEffect(() => {
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, '0');
        const firstDayOfMonth = `${currentYear}-${currentMonth}-01`;
        const formattedCurrentDate = currentDate.toISOString().split("T")[0];

        setData(prevData => ({
            ...prevData,
            dari_tanggal: firstDayOfMonth,
            sampai_tanggal: formattedCurrentDate
        }));
    }, []);

    const onJenisPenjaminChange = (selectedOption) => {
        setData(prevData => ({ ...prevData, jenisPenjamin: selectedOption.value }));
    };

    const onKeadaanPulangChange = (selectedOption) => {
        setData(prevData => ({ ...prevData, keadaanPulang: selectedOption.value }));
    };

    const onSubmit = (e) => {
        e.preventDefault();

        // Filter out empty values
        const filteredData = Object.fromEntries(Object.entries(data).filter(([_, v]) => v !== ''));

        const queryString = new URLSearchParams(filteredData).toString();
        window.open(route("layananPulang.print") + "?" + queryString, "_blank");
    };

    return (
        <div className="pt-2">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg">
                    <form
                        onSubmit={onSubmit}
                        className="p-4 sm-8 bg-white dark:bg-indigo-950 shadow sm:rounded-lg"
                    >
                        <h1 className="uppercase text-center font-bold text-2xl pt-2 text-white">Laporan Pasien Pulang</h1>

                        <div className="mt-4 flex space-x-4">
                            <div className="flex-1">
                                <InputLabel
                                    htmlFor="jenisPenjamin"
                                    value="Jenis Penjamin"
                                />
                                <SelectTwoInput
                                    id="jenisPenjamin"
                                    name="jenisPenjamin"
                                    className="mt-1 block w-full"
                                    placeholder="Pilih Jenis Penjamin"
                                    onChange={onJenisPenjaminChange}
                                    options={[
                                        { value: 1, label: 'Penjamin Non BPJS Kesehatan' },
                                        { value: 2, label: 'Penjamin BPJS Kesehatan' },
                                    ]}
                                />
                            </div>
                            <div className="flex-1">
                                <InputLabel
                                    htmlFor="keadaanPulang"
                                    value="Keadaan Pulang"
                                />
                                <SelectTwoInput
                                    id="keadaanPulang"
                                    name="keadaanPulang"
                                    className="mt-1 block w-full"
                                    placeholder="Pilih Keadaan Pulang"
                                    options={Array.isArray(keadaanPulang) ?
                                        keadaanPulang.map((item) => ({
                                            value: item.ID,
                                            label: item.ID + '. ' + item.DESKRIPSI,
                                        })) : []}
                                    onChange={onKeadaanPulangChange}
                                    isClearable={true}
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

