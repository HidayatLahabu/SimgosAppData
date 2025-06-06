import React, { useEffect } from "react";
import { useForm } from "@inertiajs/react";
import SelectTwoInput from "@/Components/Select/SelectTwoInput";
import InputLabel from "@/Components/Input/InputLabel";
import TextInput from "@/Components/Input/TextInput";

export default function Cetak({ ruangan = [] }) {
    const { data, setData } = useForm({
        dari_tanggal: "",
        sampai_tanggal: "",
        ruangan: "",
        statusKunjungan: "",
        pasien: "",
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
        if (selectedOption && selectedOption.value) {
            setData(prevData => ({ ...prevData, ruangan: selectedOption.value }));
        } else {
            setData(prevData => ({ ...prevData, ruangan: '' }));  // Atau handling sesuai kebutuhan
        }
    };

    const handleStatusChange = (selectedOption) => {
        if (selectedOption && selectedOption.value) {
            setData(prevData => ({ ...prevData, statusKunjungan: selectedOption.value }));
        } else {
            setData(prevData => ({ ...prevData, statusKunjungan: '' }));  // Atau handling sesuai kebutuhan
        }
    };

    const handlePasienChange = (selectedOption) => {
        if (selectedOption && selectedOption.value) {
            setData(prevData => ({ ...prevData, pasien: selectedOption.value }));
        } else {
            setData(prevData => ({ ...prevData, pasien: '' }));  // Atau handling sesuai kebutuhan
        }
    };

    const onSubmit = (e) => {
        e.preventDefault();

        // Filter out empty values
        const filteredData = Object.fromEntries(Object.entries(data).filter(([_, v]) => v !== ''));

        const queryString = new URLSearchParams(filteredData).toString();
        window.open(route("kunjungan.print") + "?" + queryString, "_blank");
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
                            Laporan Kunjungan Pasien
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
                                <InputLabel htmlFor="statusKunjungan" value="Status Aktifitas Kunjungan" />
                                <SelectTwoInput
                                    id="statusKunjungan"
                                    name="statusKunjungan"
                                    className="mt-1 block w-full"
                                    placeholder="Pilih Status Aktifitas Kunjungan"
                                    onChange={handleStatusChange}
                                    options={[
                                        { value: 1, label: 'Batal Kunjungan' },
                                        { value: 2, label: 'Sedang Dilayani' },
                                        { value: 3, label: 'Selesai' },
                                    ]}
                                />

                            </div>
                            <div className="flex-1">
                                <InputLabel htmlFor="pasien" value="Status Kunjungan" />
                                <SelectTwoInput
                                    id="pasien"
                                    name="pasien"
                                    className="mt-1 block w-full"
                                    placeholder="Pilih Status Kunjungan"
                                    onChange={handlePasienChange}
                                    options={[
                                        { value: 1, label: 'Baru' },
                                        { value: 2, label: 'Lama' },
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
