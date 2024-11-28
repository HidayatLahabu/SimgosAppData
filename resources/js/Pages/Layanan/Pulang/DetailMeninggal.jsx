export default function DetailMeninggal({ detailMeninggal }) {
    if (!detailMeninggal || !Array.isArray(detailMeninggal)) {
        return (
            <div className="text-center py-5">
                <p className="text-gray-500 dark:text-gray-300">No data available for Meninggal details.</p>
            </div>
        );
    }

    return (
        <div className="py-5">
            <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                        <div className="overflow-auto w-full">
                            <h1 className="uppercase text-center font-bold text-xl pb-2">
                                DATA DETAIL HASIL LAYANAN LABORATORIUM
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
                                    {detailMeninggal.map((detailItem, index) => (
                                        <tr key={index} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                            <td className="px-3 py-3 w-16">{index + 1}</td>
                                            <td className="px-3 py-3 w-56">{detailItem.uraian}</td>
                                            <td className="px-3 py-3 break-words">
                                                {detailItem.uraian === "STATUS" ? (
                                                    detailItem.value === 1 ? "Belum Final" :
                                                        detailItem.value === 2 ? "Sudah Final" :
                                                            detailItem.value
                                                ) : detailItem.uraian === "JENIS_LAHIR_MATI" ? (
                                                    detailItem.value === 1 ? "Iya" :
                                                        detailItem.value === 2 ? "Tidak" :
                                                            ''
                                                ) : detailItem.uraian === "PERISTIWA_PERSALINAN" ? (
                                                    detailItem.value === 0 ? "Tidak" :
                                                        detailItem.value === 1 ? "Ya" :
                                                            ''
                                                ) : detailItem.uraian === "PERISTIWA_KEHAMILAN" ? (
                                                    detailItem.value === 0 ? "Tidak" :
                                                        detailItem.value === 1 ? "Ya" :
                                                            ''
                                                ) : detailItem.uraian === "DILAKUKAN_OPERASI" ? (
                                                    detailItem.value === 0 ? "Tidak" :
                                                        detailItem.value === 1 ? "Ya" :
                                                            ''
                                                ) : detailItem.uraian === "STATUS_VERIFIKASI" ? (
                                                    detailItem.value === 0 ? "Tidak Diverifikasi" :
                                                        detailItem.value === 1 ? "Menunggu" :
                                                            detailItem.value === 2 ? "Terverifikasi" :
                                                                ''
                                                ) : detailItem.value}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
