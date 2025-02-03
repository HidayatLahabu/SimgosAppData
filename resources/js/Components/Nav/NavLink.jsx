import { Link } from '@inertiajs/react';

export default function NavLink({ active = false, className = '', children, ...props }) {
    return (
        <Link
            {...props}
            className={
                'inline-flex items-center px-4 py-2 text-sm font-bold leading-5 transition duration-300 ease-in-out focus:outline-none ' +
                (active
                    ? 'bg-indigo-500 dark:bg-amber-400 text-white dark:text-gray-900 rounded-lg shadow-xl transform scale-105'
                    : 'bg-transparent text-gray-700 dark:text-amber-400 hover:text-white rounded-lg dark:hover:text-gray-200 dark:hover:bg-indigo-500 focus:text-white dark:focus:text-gray-200 focus:bg-indigo-600 dark:focus:bg-amber-600') +
                className
            }
        >
            {children}
        </Link>
    );
}
