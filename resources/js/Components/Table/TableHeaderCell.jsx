import React from "react";

const TableHeaderCell = ({ children, className = "" }) => {
    const baseClasses = "px-3 py-2 border border-gray-500 dark:border-gray-600";
    return (
        <th className={`${baseClasses} ${className}`}>
            {children}
        </th>
    );
};

export default TableHeaderCell;
