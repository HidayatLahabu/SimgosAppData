import LoginLogo from '@/Components/LoginLogo';
import { Link } from '@inertiajs/react';

export default function Guest({ children }) {
    return (
        <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-950">
            <div>
                <Link href="/">
                    <LoginLogo className="flex flex-col items-center w-20 h-20 fill-current text-gray-500" />
                </Link>
            </div>

            <div className="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-indigo-950 shadow-md overflow-hidden sm:rounded-lg">
                {children}
            </div>
        </div>
    );
}
