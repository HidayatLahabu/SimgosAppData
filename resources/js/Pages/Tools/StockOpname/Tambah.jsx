import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Tambah({ auth, stokOpname, barangBaru }) {
    const { data, setData, post, processing } = useForm({
        stok_opname_id: stokOpname?.idSo ?? '',
        barang_ruangan_id: barangBaru.length > 0 ? barangBaru[0].id_barang_ruangan : '',
        tanggal: stokOpname?.tanggal ?? '',
        exd: '',
        awal: 0.00,
        sistem: 0.00,
        manual: 0.00,
        barang_masuk: 0.00,
        barang_keluar: 0.00,
        oleh: 50,
        status: 1,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('toolsSO.store', stokOpname.idSo));
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Tambah Barang Stock Opname" />

            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5 pt-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">

                            <div className="text-center mb-2">
                                <h1 className="text-xl font-bold uppercase tracking-wide">
                                    Tambah Barang Stok Opname
                                </h1>
                            </div>

                            {/* INFORMASI STOCK OPNAME */}
                            <div className="mb-6 p-4 bg-gray-50 dark:bg-indigo-800 border rounded">
                                <h2 className="text-lg font-semibold mb-2">Informasi Stock Opname</h2>
                                <p>ID: <span className="font-medium">{stokOpname?.idSo}</span></p>
                                <p>Ruangan: <span className="font-medium">{stokOpname?.nama_ruangan}</span></p>
                                <p>Tanggal: <span className="font-medium">{stokOpname?.tanggal ?? '-'}</span></p>
                            </div>

                            <form onSubmit={submit} className="space-y-6">

                                {/* DROPDOWN BARANG RUANGAN */}
                                <div className="flex flex-col p-3 rounded bg-white dark:bg-indigo-900 border border-amber-300 text-sm">
                                    <label className="text-amber-700 dark:text-amber-300 font-medium mb-1">
                                        Barang Ruangan
                                    </label>
                                    <select
                                        value={data.barang_ruangan_id}
                                        onChange={e => setData('barang_ruangan_id', Number(e.target.value))}
                                        className="mt-1 rounded px-2 py-1 bg-white dark:bg-indigo-950 border border-amber-300"
                                    >
                                        {barangBaru.length > 0 ? barangBaru.map(item => (
                                            <option key={item.id_barang_ruangan} value={item.id_barang_ruangan}>
                                                {item.id_barang_ruangan} - {item.nama_barang} ({item.nama_ruangan})
                                            </option>
                                        )) : (
                                            <option value="">Tidak ada barang tersedia</option>
                                        )}
                                    </select>
                                </div>

                                {/* TANGGAL & EXD */}
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div className="flex flex-col p-3 rounded bg-white dark:bg-indigo-900 border border-amber-300 text-sm">
                                        <label className="text-amber-700 dark:text-amber-300 font-medium">Tanggal</label>
                                        <input
                                            type="date"
                                            value={data.tanggal || stokOpname?.tanggal || ''}
                                            readOnly
                                            className="mt-1 rounded px-2 py-1 bg-gray-100 dark:bg-indigo-800 border border-amber-300 cursor-not-allowed"
                                        />
                                    </div>

                                    <div className="flex flex-col p-3 rounded bg-white dark:bg-indigo-900 border border-amber-300 text-sm">
                                        <label className="text-amber-700 dark:text-amber-300 font-medium">EXD</label>
                                        <input
                                            type="date"
                                            value={data.exd}
                                            onChange={e => setData('exd', e.target.value)}
                                            className="mt-1 rounded px-2 py-1 bg-white dark:bg-indigo-950 border border-amber-300"
                                        />
                                    </div>
                                </div>

                                {/* NILAI DEFAULT */}
                                <div className="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-5 gap-4">
                                    {['awal', 'sistem', 'manual', 'barang_masuk', 'barang_keluar'].map(field => (
                                        <div key={field} className="flex flex-col p-3 rounded bg-white dark:bg-indigo-900 border border-amber-300 text-sm">
                                            <label className="text-amber-700 dark:text-amber-300 font-medium">
                                                {field.replace('_', ' ').toUpperCase()}
                                            </label>
                                            <input
                                                type="number"
                                                step="0.01"
                                                value={data[field]}
                                                onChange={e => setData(field, parseFloat(e.target.value))}
                                                className="mt-1 rounded px-2 py-1 bg-white dark:bg-indigo-950 border border-amber-300"
                                            />
                                        </div>
                                    ))}
                                </div>

                                <input
                                    type="hidden"
                                    name="tanggal"
                                    value={stokOpname?.tanggal ?? ''}
                                />

                                {/* ACTION */}
                                <div className="flex justify-between pt-4">
                                    <a
                                        href={route('toolsSO.list', { id: stokOpname.idSo })}
                                        className="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white text-sm"
                                    >
                                        Kembali
                                    </a>

                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="px-5 py-2 rounded bg-amber-500 hover:bg-amber-600 text-gray-900 font-semibold text-sm disabled:opacity-50"
                                    >
                                        Simpan
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
