import LoginLogo from '@/Components/LoginLogo';
import { Link } from '@inertiajs/react';

export default function Guest({ children }) {

    const gradientBackground = {
        backgroundImage: 'linear-gradient(to left top, #096b22, #005b3e, #00484a, #003545, #002232, #00202e, #001e2b, #001c27, #002930, #00362d, #04421e, #354a06)',
        minHeight: '100vh',
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'center',
        alignItems: 'center',
        paddingTop: '1.5rem',
    };

    return (
        <div style={gradientBackground}>
            <div>
                <Link href="/">
                    <LoginLogo className="flex flex-col items-center w-20 h-20 fill-current text-gray-500" />
                </Link>
            </div>

            <div className="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden">
                {children}
            </div>
        </div>
    );
}
