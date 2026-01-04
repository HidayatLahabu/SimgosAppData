import React, { useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Edit({ auth, stokOpname }) {

    const { data, setData, put, processing } = useForm({
        awal: 0,
        sistem: 0,
        manual: 0,
        barang_masuk: 0,
        barang_keluar: 0,
    });

    // 🔥 INI KUNCI UTAMA
    useEffect(() => {
        if (stokOpname) {
            setData({
                awal: stokOpname.awal ?? 0,
                sistem: stokOpname.sistem ?? 0,
                manual: stokOpname.manual ?? 0,
                barang_masuk: stokOpname.barang_masuk ?? 0,
                barang_keluar: stokOpname.barang_keluar ?? 0,
            });
        }
    }, [stokOpname]);

    const submit = (e) => {
        e.preventDefault();
        put(route('toolsSO.update', stokOpname.idSod));
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Edit Stok Opname" />

            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5 pt-5">
                <div className="bg-white dark:bg-indigo-900 shadow-sm sm:rounded-lg">
                    <div className="p-5 dark:bg-indigo-950">

                        <h1 className="text-xl font-bold text-white text-center mb-4">
                            Edit STOK OPNAME DETAIL
                        </h1>

                        {/* INFO BARANG */}
                        <div className="mb-4 px-4 py-3 bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-lg text-white text-sm shadow uppercase">
                            <span className="font-semibold">NAMA BARANG: {stokOpname?.nama_barang}</span>
                            <span className="mx-2">•</span>
                            <span>RUANGAN: {stokOpname?.ruangan}</span>
                            <span className="mx-2">•</span>
                            <span>SATUAN: {stokOpname?.satuan}</span>
                            <span className="mx-2">•</span>
                            <span className="bg-white/20 px-2 py-0.5 rounded">
                                ID STOK OPNAME DETAIL: {stokOpname?.idSod}
                            </span>
                            <span className="mx-2">•</span>
                            <span className="bg-white/20 px-2 py-0.5 rounded">
                                ID STOK OPNAME: {stokOpname?.idSo}
                            </span>
                        </div>


                        <form
                            onSubmit={submit}
                            className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4"
                        >
                            {[
                                { key: 'awal', label: 'AWAL' },
                                { key: 'sistem', label: 'SISTEM' },
                                { key: 'manual', label: 'MANUAL' },
                                { key: 'barang_masuk', label: 'BARANG MASUK' },
                                { key: 'barang_keluar', label: 'BARANG KELUAR' },
                            ].map(item => (
                                <div key={item.key}>
                                    <label className="block text-sm font-medium mb-1 text-gray-700 dark:text-white">
                                        {item.label}
                                    </label>
                                    <input
                                        type="number"
                                        step="1"
                                        value={data[item.key]}
                                        onChange={e =>
                                            setData(item.key, parseInt(e.target.value || 0))
                                        }
                                        className="w-full rounded border px-2 py-1"
                                    />
                                </div>
                            ))}

                            {/* ACTION */}
                            <div className="col-span-full flex justify-between mt-4">
                                <a
                                    href={route('toolsSO.list', { id: stokOpname?.idSo })}
                                    className="px-4 py-2 bg-red-500 text-white rounded"
                                >
                                    Kembali
                                </a>

                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-4 py-2 bg-amber-500 font-semibold rounded disabled:opacity-50"
                                >
                                    Simpan
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
