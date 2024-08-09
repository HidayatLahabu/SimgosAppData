import React from 'react';

const cardColors = [
    'bg-red-500',
    'bg-green-500',
    'bg-blue-500',
    'bg-yellow-500',
    'bg-purple-500',
    'bg-pink-500',
    'bg-indigo-500',
    'bg-teal-500',
    'bg-orange-500',
    'bg-gray-500'
];

export default function DashboardCards({ items = [] }) {
    // Debugging: log items to see what data is being received
    console.log('Received items:', items);

    // Check if items is defined and is an array
    if (!Array.isArray(items)) {
        console.error('Expected items to be an array but received:', items);
        return <div>Error: Data not available</div>;
    }

    return (
        <div className="max-w-full mx-auto sm:px-5 lg:px-5 w-full">
            <div className="bg-white dark:bg-indigo-950 overflow-hidden shadow-sm sm:rounded-lg w-full">
                <div className="p-5 text-gray-900 dark:text-gray-100 w-full">
                    <h1 className="uppercase text-center font-bold text-2xl pb-2">DATA SATU SEHAT</h1>
                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                        {items.length > 0 ? (
                            items.map((item, index) => (
                                <div
                                    key={index}
                                    className={`${cardColors[index % cardColors.length]} p-4 rounded-lg shadow-md text-white`}
                                >
                                    <h2 className="font-bold text-lg uppercase">{item.NAMA}</h2>
                                    <div className="flex justify-between mt-2">
                                        <div className="text-left">
                                            <p className="font-bold">Total: </p>
                                            <p className="font-bold">{item.TOTAL}</p>
                                        </div>
                                        <div className="text-right">
                                            <p>Memiliki ID: {item.MEMILIKI_ID}</p>
                                            <p>Tidak Memiliki ID: {item.TDK_MEMILIKI_ID}</p>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="text-center col-span-full">No data available</div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
