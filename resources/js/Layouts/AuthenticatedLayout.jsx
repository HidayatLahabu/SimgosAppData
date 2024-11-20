import React from 'react';
import TopNavigation from './TopNavigation';

export default function Authenticated({ user, children }) {

    return (
        <div className="min-h-screen bg-gray-100 dark:bg-gray-950">

            <TopNavigation user={user} />

            <main className='pt-16'>{children}</main>

            <footer className="bg-gray-50 text-black/50 dark:bg-gray-950 dark:text-gray-200">
                <div className="text-center">
                    <p>&copy; {new Date().getFullYear()} Hidayat - Tim IT RSUD Dr. M. M. Dunda Limboto. All rights reserved.</p>
                </div>
            </footer>
        </div>
    );
}
