import React from 'react';

export default function DetailCatatan({ detailCatatan }) {
    // Handle the case where detailCatatan might be null or undefined
    const detailData = detailCatatan ? Object.keys(detailCatatan).map((key) => ({
        uraian: key,
        value: detailCatatan[key],
    })) : [];

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DATA DETAIL CATATAN HASIL LAYANAN LABORATORIUM
                            </h1>
                            <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                    <tr>
                                        <th className="px-3 py-2">NO</th>
                                        <th className="px-3 py-2">COLUMN</th>
                                        <th className="px-3 py-2">VALUE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {detailData.length > 0 ? (
                                        detailData.map((detailItem, index) => (
                                            <tr key={index} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td className="px-3 py-3 w-16">{index + 1}</td>
                                                <td className="px-3 py-3 w-56">{detailItem.uraian}</td>
                                                {/* <td className="px-3 py-3 break-words">{detailItem.value}</td> */}
                                                <td className="px-3 py-3 break-words">
                                                    {detailItem.uraian === "STATUS" ? (
                                                        detailItem.value === 0 ? "Belum Final" :
                                                            detailItem.value === 1 ? "Sudah Final" :
                                                                detailItem.value
                                                    ) : detailItem.value}
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="3" className="px-3 py-3 text-center">No data available</td>
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
