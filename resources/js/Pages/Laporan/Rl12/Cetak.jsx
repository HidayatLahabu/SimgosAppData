import React from "react";
import { useForm } from "@inertiajs/react";
import InputLabel from "@/Components/Input/InputLabel";
import TextInput from "@/Components/Input/TextInput";

export default function Cetak() {

    const { data, setData } = useForm({
        tempatTidur: '',
        tahun: ''
    });

    const onSubmit = (e) => {
        e.preventDefault();

        // Filter out empty values
        const filteredData = Object.fromEntries(Object.entries(data).filter(([_, v]) => v !== ''));

        const queryString = new URLSearchParams(filteredData).toString();
        window.open(route("laporanRl12.print") + "?" + queryString, "_blank");
    };

    return (
        <div className="pt-2">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5 pb-2">
                <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg">
                    <form
                        onSubmit={onSubmit}
                        className="p-4 sm-8 bg-white dark:bg-indigo-950 shadow sm:rounded-lg"
                    >
                        <h1 className="uppercase text-center font-bold text-2xl pt-2 text-white">Cetak Laporan RL 1.2</h1>

                        <div className="mt-4 flex space-x-4">
                            <div className="flex-1">
                                <InputLabel
                                    htmlFor="tempatTidur"
                                    value="Tempat Tidur"
                                />
                                <TextInput
                                    type="number"
                                    id="tempatTidur"
                                    name="tempatTidur"
                                    value={data.tempatTidur}
                                    className="mt-1 block w-full"
                                    placeholder="Masukkan jumlah tempat tidur"
                                    onChange={(e) => setData('tempatTidur', e.target.value)}
                                />
                            </div>
                            <div className="flex-1">
                                <InputLabel
                                    htmlFor="tahun"
                                    value="Tahun Pelayanan"
                                />
                                <TextInput
                                    type="number"
                                    id="tahun"
                                    name="tahun"
                                    value={data.tahun}
                                    className="mt-1 block w-full"
                                    placeholder="Masukkan Tahun Pelayanan"
                                    onChange={(e) => setData('tahun', e.target.value)}
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

