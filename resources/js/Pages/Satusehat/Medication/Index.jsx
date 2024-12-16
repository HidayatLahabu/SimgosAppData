import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import Table from "@/Components/Table";
import TableHeader from "@/Components/TableHeader";
import TableHeaderCell from "@/Components/TableHeaderCell";
import TableRow from "@/Components/TableRow";
import TableCell from "@/Components/TableCell";

export default function Index({ auth, dataTable, queryParams = {} }) {

    const headers = [
        { name: "ID" },
        { name: "MANUFACTURER" },
        { name: "REF ID" },
        { name: "NOPEN" },
        { name: "SEND DATE" },
    ];

    // Function to handle search input changes
    const searchFieldChanged = (manufacturer, value) => {
        const updatedParams = { ...queryParams, page: 1 }; // Reset to the first page
        if (value) {
            updatedParams[manufacturer] = value;
        } else {
            delete updatedParams[manufacturer];
        }
        // Update the URL and fetch new data based on updatedParams
        router.get(route('medication.index'), updatedParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Function to handle change in search input
    const onInputChange = (manufacturer, e) => {
        const value = e.target.value;
        searchFieldChanged(manufacturer, value);
    };

    // Function to handle Enter key press in search input
    const onKeyPress = (manufacturer, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(manufacturer, e.target.value);
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
                                <h1 className="uppercase text-center font-bold text-2xl pb-2">Data Medication</h1>
                                <Table>
                                    <TableHeader>
                                        <tr>
                                            <th colSpan={5} className="px-3 py-2">
                                                <TextInput
                                                    className="w-full"
                                                    defaultValue={queryParams.manufacturer || ''}
                                                    placeholder="Cari medication"
                                                    onChange={e => onInputChange('manufacturer', e)}
                                                    onKeyPress={e => onKeyPress('manufacturer', e)}
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
                                                <TableRow key={data.nopen} isEven={index % 2 === 0}>
                                                    <TableCell>{data.id}</TableCell>
                                                    <TableCell>{data.manufacturer}</TableCell>
                                                    <TableCell>{data.refId}</TableCell>
                                                    <TableCell>{data.nopen}</TableCell>
                                                    <TableCell>{data.sendDate}</TableCell>
                                                </TableRow>
                                            ))
                                        ) : (
                                            <tr className="bg-white border-b dark:bg-indigo-950 dark:border-gray-500">
                                                <td colSpan="5" className="px-3 py-3 text-center">Tidak ada data yang dapat ditampilkan</td>
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
