import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';


const toDatetimeLocal = (value) => {
    if (!value) return '';
    return value.replace(' ', 'T');
};

const formatDbDatetime = (value) => value ?? '-';

export default function Edit({ auth, kunjungan }) {

    const { data, setData, put, processing } = useForm({
        nomor_pendaftaran: kunjungan.nomor_pendaftaran,
        nomor_kunjungan: kunjungan.nomor_kunjungan,
        norm: kunjungan.norm,
        nama_pasien: kunjungan.nama_pasien,
        ruangan_tujuan: kunjungan.ruangan_tujuan,
        dpjp: kunjungan.dpjp,

        // EDITABLE
        tanggal_masuk: toDatetimeLocal(kunjungan.tanggal_masuk),
        tanggal_keluar: toDatetimeLocal(kunjungan.tanggal_keluar),
        status_kunjungan: kunjungan.status_kunjungan ?? 1,
    });

    const submit = (e) => {
        e.preventDefault();
        put(route('kunjungan.update', data.nomor_kunjungan));
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Edit Kunjungan" />

            <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 shadow-sm rounded-lg">
                        <div className="p-5 dark:bg-indigo-950">

                            {/* ===== HEADER ===== */}
                            <div className="text-center mb-2">
                                <h1 className="text-xl font-bold uppercase tracking-wide">
                                    Edit Kunjungan
                                </h1>
                                <p className="text-sm text-red-500 dark:text-red-500">
                                    *) Field berwarna kuning dapat diubah
                                </p>
                            </div>

                            <form onSubmit={submit} className="space-y-8">

                                {/* ======================
                                INFORMASI (READONLY)
                                ======================= */}
                                <Section title="Informasi Kunjungan">
                                    <Grid>
                                        <Info label="No Pendaftaran" value={data.nomor_pendaftaran} />
                                        <Info label="No Kunjungan" value={data.nomor_kunjungan} />
                                        <Info label="NORM" value={data.norm} />
                                        <Info label="Nama Pasien" value={data.nama_pasien} />
                                    </Grid>

                                    <Grid>
                                        <Info label="Ruangan Tujuan" value={data.ruangan_tujuan} />
                                        <Info label="DPJP" value={data.dpjp} />
                                        <Info label="Tanggal Masuk" value={formatDbDatetime(kunjungan.tanggal_masuk)} />
                                        <Info label="Tanggal Keluar" value={formatDbDatetime(kunjungan.tanggal_keluar)} />
                                    </Grid>
                                </Section>

                                {/* ======================
                                EDITABLE AREA
                                ======================= */}
                                <SectionEdit title="Edit Data Kunjungan">
                                    <GridEdit>
                                        <Editable
                                            label="Tanggal Masuk"
                                            type="datetime-local"
                                            step="1"
                                            value={data.tanggal_masuk}
                                            onChange={e => setData('tanggal_masuk', e.target.value)}
                                        />

                                        <Editable
                                            label="Tanggal Keluar"
                                            type="datetime-local"
                                            step="1"
                                            value={data.tanggal_keluar}
                                            onChange={e => setData('tanggal_keluar', e.target.value)}
                                        />

                                        <SelectStatus
                                            label="Status Kunjungan"
                                            value={data.status_kunjungan}
                                            onChange={e => setData('status_kunjungan', e.target.value)}
                                        />
                                    </GridEdit>
                                </SectionEdit>

                                {/* ======================
                                ACTION
                                ======================= */}
                                <div className="flex justify-between pt-2">
                                    <a
                                        href={route('kunjungan.detail', data.nomor_kunjungan)}
                                        className="
                                            px-4 py-2 rounded
                                            bg-red-500 hover:bg-red-600
                                            text-white text-sm transition
                                        "
                                    >
                                        Kembali
                                    </a>

                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="
                                            px-5 py-2 rounded
                                            bg-amber-500 hover:bg-amber-600
                                            text-gray-900 font-semibold text-sm
                                            transition
                                            disabled:opacity-50
                                        "
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

/* =======================
   COMPONENT HELPER
======================= */

const Section = ({ title, children }) => (
    <div className="space-y-4">
        <h2 className="text-lg font-semibold border-b pb-2">
            {title}
        </h2>
        {children}
    </div>
);

const SectionEdit = ({ title, children }) => (
    <div className="
        space-y-4
        border-l-4 border-amber-400
        bg-amber-50/60 dark:bg-indigo-800/40
        p-4 rounded-md
    ">
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
    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        {children}
    </div>
);

const Info = ({ label, value }) => (
    <div className="
        flex flex-col p-3 rounded
        bg-gray-50 dark:bg-indigo-800
        border border-gray-200 dark:border-indigo-700
        text-sm
    ">
        <span className="text-gray-500 dark:text-gray-300">{label}</span>
        <span className="font-medium text-gray-800 dark:text-gray-100">
            {value ?? '-'}
        </span>
    </div>
);

const Editable = ({ label, type, value, onChange }) => (
    <div className="
        flex flex-col p-3 rounded
        bg-white dark:bg-indigo-900
        border border-amber-300 dark:border-amber-400
        text-sm
    ">
        <label className="text-amber-700 dark:text-amber-300 font-medium">
            {label}
        </label>
        <input
            type={type}
            value={value}
            onChange={onChange}
            className="
                mt-1 rounded px-2 py-1
                bg-white dark:bg-indigo-950
                border border-amber-300
                focus:ring-2 focus:ring-amber-400
                text-gray-900 dark:text-gray-100
            "
        />
    </div>
);

const SelectStatus = ({ label, value, onChange }) => (
    <div className="
        flex flex-col p-3 rounded
        bg-white dark:bg-indigo-900
        border border-amber-300 dark:border-amber-400
        text-sm
    ">
        <label className="text-amber-700 dark:text-amber-300 font-medium">
            {label}
        </label>
        <select
            value={value}
            onChange={onChange}
            className="
                mt-1 rounded px-2 py-1
                bg-white dark:bg-indigo-950
                border border-amber-300
                focus:ring-2 focus:ring-amber-400
                text-gray-900 dark:text-gray-100
            "
        >
            <option value={0}>Batal</option>
            <option value={1}>Sedang Dilayani</option>
            <option value={2}>Selesai</option>
        </select>
    </div>
);
