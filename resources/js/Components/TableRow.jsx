// TableRow.jsx
export default function TableRow({ children, isEven, className = "" }) {
    return (
        <tr
            className={`hover:bg-indigo-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 ${isEven ? 'bg-transparent' : 'bg-transparent'
                } ${className}`}
        >
            {children}
        </tr>
    );
}
