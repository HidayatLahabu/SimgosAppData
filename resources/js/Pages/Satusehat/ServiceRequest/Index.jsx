import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";

export default function Index({ auth, serviceRequest, queryParams = {} }) {

    // Function to handle search input changes
    const searchFieldChanged = (subject, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[subject] = value;
        } else {
            delete updatedParams[subject];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('serviceRequest.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (subject, e) => {
        const value = e.target.value;
        searchFieldChanged(subject, value);
    };

    // Function to handle Enter key press in search input
    const onKeyPress = (subject, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(subject, e.target.value);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="SatuSehat" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Service Request</h1>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th colSpan={5} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.subject || ''}
                                                    placeholder="Cari service request"
                                                    onChange={e => onInputChange('subject', e)}
                                                    onKeyPress={e => onKeyPress('subject', e)}
                                                />
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">ID</th>
                                            <th className="px-3 py-2">SUBJECT</th>
                                            <th className="px-3 py-2">REF ID</th>
                                            <th className="px-3 py-2">NOPEN</th>
                                            <th className="px-3 py-2">SEND DATE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {serviceRequest.data.length > 0 ? (
                                            serviceRequest.data.map((serviceRequest, index) => (
                                                <tr key={`${serviceRequest.id}-${index}`} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                    <td className="px-3 py-3">{serviceRequest.id}</td>
                                                    <td className="px-3 py-3">{serviceRequest.subject}</td>
                                                    <td className="px-3 py-3">{serviceRequest.refId}</td>
                                                    <td className="px-3 py-3">{serviceRequest.nopen}</td>
                                                    <td className="px-3 py-3">{serviceRequest.sendDate}</td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="5" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                                <Pagination links={serviceRequest.links} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
