// TableCellMenu.jsx
import React from 'react';

const TableCellMenu = ({ children }) => {
    return (
        <td className="py-2 text-center flex justify-center items-center">
            {children}
        </td>
    );
};

export default TableCellMenu;
