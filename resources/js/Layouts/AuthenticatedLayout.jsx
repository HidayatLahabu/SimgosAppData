import React from 'react';
import TopNavigation from './TopNavigation';

export default function Authenticated({ user, children }) {

    const gradientBackground = {
        backgroundImage: 'linear-gradient(to left top, #096b22, #005b3e, #00484a, #003545, #002232, #00202e, #001e2b, #001c27, #002930, #00362d, #04421e, #354a06)',
    };

    return (
        <div className="min-h-screen bg-gray-100 dark:bg-gray-950">

            <TopNavigation user={user} />

            <main className='pt-16'>
                {children}
            </main>

            <footer className="bg-gray-50 text-black/50 dark:bg-gray-950 dark:text-gray-200">
                <div className="text-center">
                    <p>&copy; {new Date().getFullYear()} Hidayat - Tim IT RSUD Dr. M. M. Dunda Limboto.
                    </p>
                </div>
            </footer>
        </div>
    );
}
