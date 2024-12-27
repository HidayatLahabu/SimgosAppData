import React from 'react';
import TopNavigation from './TopNavigation';

export default function Authenticated({ user, children }) {

    const gradientBackground = {
        backgroundImage: 'linear-gradient(to right top, #04080e, #001015, #001615, #051a11, #151c07',
    };

    return (
        // <div className="min-h-screen bg-gray-100 dark:bg-gray-950">
        <div className="min-h-screen" style={gradientBackground}>

            <TopNavigation user={user} />

            <main className='pt-16'>
                {children}
            </main>

            <footer className="text-black/50 dark:text-gray-200">
                <div className="text-center">
                    <p>&copy; {new Date().getFullYear()} Hidayat - Tim IT RSUD Dr. M. M. Dunda Limboto.
                    </p>
                </div>
            </footer>
        </div>
    );
}
