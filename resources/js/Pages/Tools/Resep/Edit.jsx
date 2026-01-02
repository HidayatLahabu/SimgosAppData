import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

/* ===== Helper ===== */
const toDatetimeLocal = (value) => {
    if (!value) return '';
    return value.replace(' ', 'T');
};

export default function Edit({ auth, kunjungan, ruanganList }) {

    const isAdmin = auth?.user?.role === 'admin';

    const { data, setData, put, processing } = useForm({
        ruangan_id: kunjungan.ruangan_id,
        tanggal: toDatetimeLocal(kunjungan.tanggal),
    });

    const submit = (e) => {
        e.preventDefault();

        if (!isAdmin) {
            alert('Anda tidak memiliki hak akses untuk mengubah data resep');
            return;
        }

        // UPDATE TANGGAL & TUJUAN ORDER_RESEP
        put(route('toolsResep.update', kunjungan.nomor));
    };

    const SelectRuangan = ({ label, value, onChange, options }) => (
        <div className="flex flex-col p-3 rounded bg-white dark:bg-indigo-900 border border-amber-300 text-sm">
            <label className="text-amber-700 dark:text-amber-300 font-medium">
                {label}
            </label>
            <select
                value={value}
                onChange={onChange}
                disabled={!isAdmin}
                className="mt-1 rounded px-2 py-1 bg-white dark:bg-indigo-950 border border-amber-300 disabled:opacity-60"
            >
                {options.map(r => (
                    <option key={r.id} value={r.id}>
                        {r.id} - {r.namaRuangan}
                    </option>
                ))}
            </select>
        </div>
    );

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Tools Edit Resep" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">

                            <div className="text-center mb-2">
                                <h1 className="text-xl font-bold uppercase tracking-wide">
                                    Edit Resep
                                </h1>
                                {!isAdmin && (
                                    <p className="text-sm text-red-500 mt-1">
                                        Anda hanya memiliki akses lihat (read-only)
                                    </p>
                                )}
                            </div>

                            <form onSubmit={submit} className="space-y-8">

                                {/* ================= INFORMASI ================= */}
                                <Section title="Informasi Resep">
                                    <Grid>
                                        <Info label="Nomor Resep" value={kunjungan.nomor} />
                                        <Info label="NORM" value={kunjungan.norm} />
                                        <Info label="Nama Pasien" value={kunjungan.nama_pasien} />
                                        <Info label="Order Oleh" value={kunjungan.dpjp} />
                                    </Grid>

                                    <Grid>
                                        <Info label="Berat Badan (kg)" value={kunjungan.berat_badan} />
                                        <Info label="Tinggi Badan (cm)" value={kunjungan.tinggi_badan} />
                                        <Info label="Pemberi Resep" value={kunjungan.pemberi_resep} />
                                        <Info
                                            label="Ruangan Tujuan Saat Ini"
                                            value={`${kunjungan.ruangan_id} - ${kunjungan.ruangan_tujuan}`}
                                        />
                                    </Grid>
                                </Section>

                                {/* ================= EDITABLE ================= */}
                                <SectionEdit title="Edit Data Resep">
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">

                                        <SelectRuangan
                                            label="Ruangan Tujuan"
                                            value={data.ruangan_id}
                                            onChange={e => setData('ruangan_id', e.target.value)}
                                            options={ruanganList}
                                        />

                                        <div className="flex flex-col p-3 rounded bg-white dark:bg-indigo-900 border border-amber-300 text-sm">
                                            <label className="text-amber-700 dark:text-amber-300 font-medium">
                                                Tanggal Resep
                                            </label>
                                            <input
                                                type="datetime-local"
                                                value={data.tanggal}
                                                onChange={e => setData('tanggal', e.target.value)}
                                                disabled={!isAdmin}
                                                className="mt-1 rounded px-2 py-1
                                                    bg-white dark:bg-indigo-950
                                                    border border-amber-300
                                                    disabled:opacity-60"
                                            />
                                        </div>
                                    </div>
                                </SectionEdit>


                                {/* ================= ACTION ================= */}
                                <div className="flex justify-between pt-2">
                                    <a
                                        href={route('toolsResep.index')}
                                        className="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white text-sm"
                                    >
                                        Kembali
                                    </a>

                                    {isAdmin && (
                                        <button
                                            type="submit"
                                            disabled={processing}
                                            className="px-5 py-2 rounded bg-amber-500 hover:bg-amber-600 text-gray-900 font-semibold text-sm disabled:opacity-50"
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
        </AuthenticatedLayout>
    );
}

/* ===== Helper Components ===== */

const Section = ({ title, children }) => (
    <div className="space-y-4">
        <h2 className="text-lg font-semibold border-b pb-2">{title}</h2>
        {children}
    </div>
);

const SectionEdit = ({ title, children }) => (
    <div className="space-y-4 border-l-4 border-amber-400 bg-amber-50/60 dark:bg-indigo-800/40 p-4 rounded-md">
        <h2 className="text-lg font-semibold text-amber-700 dark:text-amber-300">
            {title}
        </h2>
        {children}
    </div>
);

const Grid = ({ children }) => (
    <div className="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3">
        {children}
    </div>
);

const GridEdit = ({ children }) => (
    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        {children}
    </div>
);

const Info = ({ label, value }) => (
    <div className="flex flex-col p-3 rounded bg-gray-50 dark:bg-indigo-800 border text-sm">
        <span className="text-gray-200">{label}</span>
        <span className="font-medium text-yellow-700 dark:text-yellow-500">
            {value ?? '-'}
        </span>
    </div>
);
