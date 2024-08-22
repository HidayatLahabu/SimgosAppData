import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";

export default function Index({ auth, dataTable, queryParams = {} }) {

    // Function to handle search input changes
    const searchFieldChanged = (nama, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[nama] = value;
        } else {
            delete updatedParams[nama];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('logsBridge.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (nama, e) => {
        const value = e.target.value;
        searchFieldChanged(nama, value);
    };

    // Function to handle Enter key press in search input
    const onKeyPress = (nama, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(nama, e.target.value);
    };

    // Function to filter the response and display only {"code":"200","message":"OK"} if found
    // const filterResponse = (response) => {
    //     const targetStrings = [
    //         '{"code":"200","message":"Sukses"}',
    //         '{"code":"200","message":"OK"}',
    //         '{"code":"200","message":"Ok"}' // Added this line for 'Ok'
    //     ];

    //     for (const targetString of targetStrings) {
    //         if (response.includes(targetString)) {
    //             return targetString;
    //         }
    //     }

    //     return response;
    // };

    const filterResponse = (response) => {
        // Define the target strings for specific messages
        const targetStrings = [
            '{"code":"200","message":"Sukses"}',
            '{"code":"200","message":"OK"}',
            '{"code":"200","message":"Ok"}'
        ];

        // Check if the response includes any of the target strings
        for (const targetString of targetStrings) {
            if (response.includes(targetString)) {
                return targetString;
            }
        }

        // Check if the response is a JSON object and filter specific fields
        try {
            const responseObject = JSON.parse(response);

            // Check if the parsed object has the expected fields
            const filteredFields = {};
            if (responseObject.hasOwnProperty("refresh_token_expires_in")) {
                filteredFields["refresh_token_expires_in"] = responseObject.refresh_token_expires_in;
            }
            if (responseObject.hasOwnProperty("api_product_list")) {
                filteredFields["api_product_list"] = responseObject.api_product_list;
            }
            if (responseObject.hasOwnProperty("api_product_list_json")) {
                filteredFields["api_product_list_json"] = responseObject.api_product_list_json;
            }
            if (responseObject.hasOwnProperty("organization_name")) {
                filteredFields["organization_name"] = responseObject.organization_name;
            }

            // Return the filtered fields as a JSON string if there are any fields
            return Object.keys(filteredFields).length > 0 ? JSON.stringify(filteredFields) : response;
        } catch (e) {
            // If JSON parsing fails, return the full response
            return response;
        }
    };


    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Logs" />

            <div className="py-5">
                <div className="max-w-8xl mx-auto sm:px-6 lg:px-5">
                    <div className="bg-white dark:bg-indigo-900 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-5 text-gray-900 dark:text-gray-100 dark:bg-indigo-950">
                            <div className="overflow-auto w-full">
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Bridge Logs</h1>
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-900">
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th colSpan={6} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.nama || ''}
                                                    placeholder="Cari url"
                                                    onChange={e => onInputChange('nama', e)}
                                                    onKeyPress={e => onKeyPress('nama', e)}
                                                />
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead className="text-sm font-bold text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-100 border-b-2 border-gray-500">
                                        <tr>
                                            <th className="px-3 py-2">ID</th>
                                            <th className="px-3 py-2">URL</th>
                                            <th className="px-3 py-2">TANGGAL</th>
                                            <th className="px-3 py-2">RESPONSE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {dataTable.data.length > 0 ? (
                                            dataTable.data.map((dataTable, index) => (
                                                <tr key={`${dataTable.ID}-${index}`} className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                    <td className="px-3 py-3">{dataTable.ID}</td>
                                                    <td className="px-3 py-3 break-words max-w-xs">{dataTable.URL}</td>
                                                    <td className="px-3 py-3">{dataTable.TGL_REQUEST}</td>
                                                    <td className="px-3 py-3 break-words max-w-xs">
                                                        {filterResponse(dataTable.RESPONSE)}
                                                    </td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="6" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                                {/* <Pagination links={dataTable.links} /> */}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
