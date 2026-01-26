import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

const toDatetimeLocal = (value) => {
    if (!value) return '';
    return value.replace(' ', 'T');
};

const formatDbDatetime = (value) => value ?? '-';

export default function Edit({ auth, kunjungan }) {

    const isAdmin = auth?.user?.role === 'admin';

    const { data, setData, put, processing } = useForm({
        nomor_pendaftaran: kunjungan.nomor_pendaftaran,
        norm: kunjungan.norm,
        nama_pasien: kunjungan.nama_pasien,
        ruangan_id: kunjungan.ruangan_id,
        ruangan_tujuan: kunjungan.ruangan_tujuan,

        // HANYA INI YANG BOLEH DIUBAH
        tanggal_masuk: toDatetimeLocal(kunjungan.tanggal_masuk),
    });

    const submit = (e) => {
        e.preventDefault();

        if (!isAdmin) {
            alert('Anda tidak memiliki hak akses');
            return;
        }

        put(route('pendaftaran.update', data.nomor_pendaftaran));
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Edit Kunjungan" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">

                                <h1 className="text-xl font-bold text-center mb-4">
                                    Edit Tanggal Masuk
                                </h1>

                                <form onSubmit={submit} className="space-y-8">

                                    {/* READONLY INFO */}
                                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                        <Info label="No Pendaftaran" value={data.nomor_pendaftaran} />
                                        <Info label="NORM" value={data.norm} />
                                        <Info label="Nama Pasien" value={data.nama_pasien} />
                                        <Info
                                            label="Ruangan Tujuan"
                                            value={`${data.ruangan_id} - ${data.ruangan_tujuan}`}
                                        />
                                        <Info
                                            label="Tanggal Masuk (DB)"
                                            value={formatDbDatetime(kunjungan.tanggal_masuk)}
                                        />
                                    </div>

                                    {/* EDIT */}
                                    <div className="border-l-4 border-amber-400 bg-amber-50/60 dark:bg-indigo-800/40 p-4 rounded-md">
                                        <h2 className="text-lg font-semibold text-amber-700 dark:text-amber-300 mb-2">
                                            Ubah Tanggal Masuk
                                        </h2>

                                        <Editable
                                            label="Tanggal Masuk"
                                            type="datetime-local"
                                            value={data.tanggal_masuk}
                                            onChange={e => setData('tanggal_masuk', e.target.value)}
                                            disabled={!isAdmin}
                                        />
                                    </div>

                                    {/* ACTION */}
                                    <div className="flex justify-between pt-2">
                                        <a
                                            href={route('pendaftaran.index')}
                                            className="px-4 py-2 rounded bg-red-500 text-white"
                                        >
                                            Kembali
                                        </a>

                                        {isAdmin && (
                                            <button
                                                type="submit"
                                                disabled={processing}
                                                className="px-5 py-2 rounded bg-amber-500 font-semibold disabled:opacity-50"
                                            >
                                                Simpan
                                            </button>
                                        )}
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </AuthenticatedLayout>
    );
}

/* ===== Helper ===== */

const Info = ({ label, value }) => (
    <div className="flex flex-col p-3 rounded bg-gray-50 dark:bg-indigo-800 border text-sm">
        <span className="text-gray-200">{label}</span>
        <span className="font-medium text-yellow-700 dark:text-yellow-500">
            {value ?? '-'}
        </span>
    </div>
);

const Editable = ({ label, type, value, onChange, disabled }) => (
    <div className="flex flex-col max-w-xs p-3 rounded bg-white dark:bg-indigo-900 border border-amber-300 text-sm">
        <label className="text-amber-700 dark:text-amber-300 font-medium">
            {label}
        </label>
        <input
            type={type}
            value={value}
            onChange={onChange}
            disabled={disabled}
            className="mt-1 rounded px-2 py-1 bg-white dark:bg-indigo-950 border border-amber-300 disabled:opacity-60"
        />
    </div>
);
