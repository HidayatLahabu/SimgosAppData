export default function TableFooter({ children }) {
    return (
        <tfoot className="text-sm font-bold text-nowrap text-gray-700 uppercase bg-gray-50 dark:bg-indigo-900 dark:text-gray-200">
            {children}
        </tfoot>
    );
}
