import React, { useEffect } from "react";
import { useForm } from "@inertiajs/react";
import SelectTwoInput from "@/Components/SelectTwoInput";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";

export default function Cetak({ ruangan = [] }) {
    const { data, setData } = useForm({
        dari_tanggal: "",
        sampai_tanggal: "",
        ruangan: "",
        statusKonsul: "",
    });

    // Utility function for initializing date values
    const initializeDates = () => {
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, "0");
        const firstDayOfMonth = `${currentYear}-${currentMonth}-01`;
        const formattedCurrentDate = currentDate.toISOString().split("T")[0];

        setData((prevData) => ({
            ...prevData,
            dari_tanggal: firstDayOfMonth,
            sampai_tanggal: formattedCurrentDate
        }));
    };

    useEffect(() => {
        initializeDates();
    }, []);

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setData((prevData) => ({ ...prevData, [name]: value }));
    };

    const onRuanganChange = (selectedOption) => {
        setData(prevData => ({ ...prevData, ruangan: selectedOption.value }));
    };

    const handleStatusChange = (selectedOption) => {
        setData(prevData => ({ ...prevData, statusKonsul: selectedOption.value }));
    };

    const onSubmit = (e) => {
        e.preventDefault();

        // Filter out empty values
        const filteredData = Object.fromEntries(Object.entries(data).filter(([_, v]) => v !== ''));

        const queryString = new URLSearchParams(filteredData).toString();
        window.open(route("konsul.print") + "?" + queryString, "_blank");
    };

    return (
        <div className="pt-2">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg">
                    <form
                        onSubmit={onSubmit}
                        className="p-4 sm-8 bg-white dark:bg-indigo-950 shadow sm:rounded-lg"
                    >
                        <h1 className="uppercase text-center font-bold text-2xl pt-2 text-white">
                            Laporan Konsul Pasien
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
                                            label: item.ID + '. ' + item.DESKRIPSI,
                                        })) : []}
                                    onChange={onRuanganChange}
                                    isClearable={true}
                                />
                            </div>
                            <div className="flex-1">
                                <InputLabel htmlFor="statusKonsul" value="Status Konsul" />
                                <SelectTwoInput
                                    id="statusKonsul"
                                    name="statusKonsul"
                                    className="mt-1 block w-full"
                                    placeholder="Pilih Status Konsul"
                                    onChange={handleStatusChange}
                                    options={[
                                        { value: 0, label: 'Batal Konsul' },
                                        { value: 1, label: 'Belum Diterima' },
                                        { value: 2, label: 'Sudah Diterima' },
                                    ]}
                                />
                            </div>
                        </div>

                        <div className="mt-4 flex space-x-4">
                            <div className="flex-1">
                                <InputLabel htmlFor="dari_tanggal" value="Dari Tanggal" />
                                <TextInput
                                    type="date"
                                    id="dari_tanggal"
                                    name="dari_tanggal"
                                    className="mt-1 block w-full"
                                    value={data.dari_tanggal}
                                    onChange={handleInputChange}
                                />
                            </div>
                            <div className="flex-1">
                                <InputLabel htmlFor="sampai_tanggal" value="Sampai Tanggal" />
                                <TextInput
                                    type="date"
                                    id="sampai_tanggal"
                                    name="sampai_tanggal"
                                    className="mt-1 block w-full"
                                    value={data.sampai_tanggal}
                                    onChange={handleInputChange}
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
    );
}
