// TableRow.jsx
export default function TableRow({ children, isEven }) {
    return (
        <tr
            className={`hover:bg-indigo-100 dark:hover:bg-indigo-800 border border-gray-500 dark:border-gray-600 ${isEven ? 'bg-gray-50 dark:bg-indigo-950' : 'bg-gray-50 dark:bg-indigo-950'
                }`}
        >
            {children}
        </tr>
    );
}
