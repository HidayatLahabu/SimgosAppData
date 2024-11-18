import React, { useEffect } from "react";
import { useForm } from "@inertiajs/react";
import SelectTwoInput from "@/Components/SelectTwoInput";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";

export default function Cetak() {

    const { data, setData } = useForm({
        dari_tanggal: '',
        sampai_tanggal: ''
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

    const onJenisKunjunganChange = (selectedOption) => {
        setData(prevData => ({ ...prevData, jenisKunjungan: selectedOption.value }));
    };

    const onSubmit = (e) => {
        e.preventDefault();

        // Filter out empty values
        const filteredData = Object.fromEntries(Object.entries(data).filter(([_, v]) => v !== ''));

        const queryString = new URLSearchParams(filteredData).toString();
        window.open(route("layananRad.print") + "?" + queryString, "_blank");
    };

    return (
        <div className="pt-2">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg">
                    <form
                        onSubmit={onSubmit}
                        className="p-4 sm-8 bg-white dark:bg-indigo-950 shadow sm:rounded-lg"
                    >
                        <h1 className="uppercase text-center font-bold text-2xl pt-2 text-white">Laporan Layanan Radiologi</h1>

                        <div className="mt-4 flex space-x-4">
                            <div className="flex-1">
                                <InputLabel
                                    htmlFor="jenisPasien"
                                    value="Jenis Penjamin"
                                />
                                <SelectTwoInput
                                    id="jenisPasien"
                                    name="jenisPasien"
                                    className="mt-1 block w-full"
                                    placeholder="Pilih Jenis Penjamin"
                                    onChange={onJenisPenjaminChange}
                                    options={[
                                        { value: 1, label: 'Penjamin Non BPJS' },
                                        { value: 2, label: 'Penjamin BPJS' },
                                    ]}
                                />
                            </div>
                            <div className="flex-1">
                                <InputLabel
                                    htmlFor="jenisKunjungan"
                                    value="Jenis Kunjungan"
                                />
                                <SelectTwoInput
                                    id="jenisKunjungan"
                                    name="jenisKunjungan"
                                    className="mt-1 block w-full"
                                    placeholder="Pilih Jenis Kunjungan"
                                    onChange={onJenisKunjunganChange}
                                    options={[
                                        { value: 1, label: 'Rawat Inap' },
                                        { value: 2, label: 'Rawat Jalan' },
                                    ]}
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

