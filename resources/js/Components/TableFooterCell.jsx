import React from "react";

const TableFooterCell = ({ children, className = "" }) => {
    const baseClasses = "px-2 py-2 border border-gray-500 dark:border-gray-600";
    return (
        <th className={`${baseClasses} ${className}`}>
            {children}
        </th>
    );
};

export default TableFooterCell;
