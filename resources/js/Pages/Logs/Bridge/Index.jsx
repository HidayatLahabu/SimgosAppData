import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/Input/TextInput";
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table/Table";
import TableHeader from "@/Components/Table/TableHeader";
import TableHeaderCell from "@/Components/Table/TableHeaderCell";
import TableRow from "@/Components/Table/TableRow";
import TableCell from "@/Components/Table/TableCell";

export default function Index({ auth, dataTable, queryParams = {} }) {

    const headers = [
        { name: "ID" },
        { name: "URL" },
        { name: "TANGGAL" },
        { name: "RESPONSE" },
    ];

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

    const filterResponse = (response) => {
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

        try {
            const responseObject = JSON.parse(response);
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

            return Object.keys(filteredFields).length > 0 ? JSON.stringify(filteredFields) : response;
        } catch (e) {
            console.error('Failed to parse JSON:', e);
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
                                <Table>
                                    <TableHeader>
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
                                    </TableHeader>
                                    <TableHeader>
                                        <tr>
                                            {headers.map((header, index) => (
                                                <TableHeaderCell key={index} className={header.className || ""}>
                                                    {header.name}
                                                </TableHeaderCell>
                                            ))}
                                        </tr>
                                    </TableHeader>
                                    <tbody>
                                        {dataTable.data.length > 0 ? (
                                            dataTable.data.map((data, index) => (
                                                <TableRow key={data.ID} isEven={index % 2 === 0}>
                                                    <TableCell>{data.ID}</TableCell>
                                                    <TableCell>{data.URL}</TableCell>
                                                    <TableCell>{data.TGL_REQUEST}</TableCell>
                                                    <TableCell>{data.RESPONSE}</TableCell>
                                                </TableRow>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="6" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
                                            </tr>
                                        )}
                                    </tbody>
                                </Table>
                                <Pagination links={dataTable.links} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
