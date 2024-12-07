// TableHeader.jsx
export default function TableHeader({ children }) {
    return (
        <thead className="text-sm font-bold text-nowrap text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-yellow-500">
            {children}
        </thead>
    );
}
