import React from 'react';

export default function DetailPoa({ detailPoa = {} }) {
    // Function to map labels with data
    const fieldMappings = [
        { label: 'ID', key: 'id' },
        { label: 'DISPLAY', key: 'display' },
        { label: 'POV', key: 'pov' },
        { label: 'NAMA DAGANG', key: 'nama_dagang' },
        { label: 'UNIT LOGISTIK TERKECIL', key: 'unit_logistik_terkecil' },
        { label: 'BENTUK SEDIAAN', key: 'bentuk_sediaan' },
        { label: 'ID BENTUK SEDIAAN', key: 'id_bentuk_sediaan' },
        { label: 'GOLONGAN OBAT', key: 'golongan_obat' },
        { label: 'URL', key: 'url' },
        { label: 'STATUS', key: 'status' },
    ];

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DATA DETAIL POA
                            </h1>
                            <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                    <tr>
                                        <th className="px-3 py-2">URAIAN</th>
                                        <th className="px-3 py-2">VALUE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {Object.keys(detailPoa).length > 0 ? (
                                        fieldMappings.map((field, index) => (
                                            <tr key={index} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td className="px-3 py-3 font-bold w-56">{field.label}</td>
                                                <td className="px-3 py-3 w-56">{detailPoa[field.key] || '-'}</td>
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
