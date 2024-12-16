// Table.jsx
export default function Table({ children }) {
    return (
        <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-200 dark:bg-indigo-950 border border-gray-500 dark:border-gray-600">
            {children}
        </table>
    );
}
