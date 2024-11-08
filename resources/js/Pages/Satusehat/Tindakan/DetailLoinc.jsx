import React from 'react';

export default function DetailLoinc({ detailLoinc = {} }) {
    // Function to map labels with data
    const fieldMappings = [
        { label: 'ID', key: 'id' },
        { label: 'KATEGORI PEMERIKSAAN', key: 'kategori_pemeriksaan' },
        { label: 'NAMA PEMERIKSAAN', key: 'nama_pemeriksaan' },
        { label: 'PERMINTAAN HASIL', key: 'permintaan_hasil' },
        { label: 'SPESIMEN', key: 'spesimen' },
        { label: 'TIPE HASIL PEMERIKSAAN', key: 'tipe_hasil_pemeriksaan' },
        { label: 'SATUAN', key: 'satuan' },
        { label: 'METODE ANALISIS', key: 'metode_analisis' },
        { label: 'CODE', key: 'code' },
        { label: 'DISPLAY', key: 'display' },
        { label: 'COMPONENT', key: 'component' },
        { label: 'PROPERTY', key: 'property' },
        { label: 'TIMING', key: 'timing' },
        { label: 'SYSTEM', key: 'system' },
        { label: 'SCALE', key: 'scale' },
        { label: 'METHOD', key: 'method' },
        { label: 'UNIT OF MEASURE', key: 'unit_of_measure' },
        { label: 'CODE SYSTEM', key: 'code_system' },
        { label: 'BODY SITE CODE', key: 'body_site_code' },
        { label: 'BODY SITE DISPLAY', key: 'body_site_display' },
        { label: 'BODY SITE CODE SISTEM', key: 'body_site_code_sistem' },
        { label: 'VERSION FIRST RELEASED', key: 'version_first_released' },
        { label: 'VERSION LAST CHANGE', key: 'version_last_change' },
    ];

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DATA DETAIL LOINC TERMINOLOGI
                            </h1>
                            <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                    <tr>
                                        <th className="px-3 py-2">URAIAN</th>
                                        <th className="px-3 py-2">VALUE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {Object.keys(detailLoinc).length > 0 ? (
                                        fieldMappings.map((field, index) => (
                                            <tr key={index} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td className="px-3 py-3 font-bold w-56">{field.label}</td>
                                                <td className="px-3 py-3 w-56">{detailLoinc[field.key] || '-'}</td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="2" className="text-center px-3 py-3">
                                                No data available
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
