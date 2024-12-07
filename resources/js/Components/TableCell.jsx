// TableCell.jsx
export default function TableCell({ children, className = "" }) {
    return (
        <td className={`px-3 py-3 border border-gray-500 dark:border-gray-600 ${className}`}>
            {children}
        </td>
    );
}
