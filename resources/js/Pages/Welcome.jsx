import { Link, Head } from '@inertiajs/react';

export default function Welcome({ auth, hospitalName }) {

    const gradientBackground = {
        backgroundImage: 'linear-gradient(to left top, #096b22, #005b3e, #00484a, #003545, #002232, #00202e, #001e2b, #001c27, #002930, #00362d, #04421e, #354a06)',
    };

    return (
        <>
            <Head title="Dunda" />
            <div className="bg-gray-50 text-black/50 dark:text-gray-200 min-h-screen flex flex-col justify-between relative" style={gradientBackground}>
                <div className="absolute top-4 right-4 flex gap-4 z-10">
                    {auth.user ? (
                        <Link
                            href={route('dashboard')}
                            className="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
                        >
                            Dashboard
                        </Link>
                    ) : (
                        <>
                            <Link
                                href={route('login')}
                                className="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600"
                            >
                                Login
                            </Link>
                            
                        </>
                    )}
                </div>
                <div className="relative flex-grow flex flex-col items-center justify-center pt-16">
                    <div className="relative w-full max-w-2xl px-6 lg:max-w-7xl flex flex-col items-center justify-center">


                        <div className="flex flex-col items-center justify-center py-8 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                            <h1 className="text-3xl font-extrabold text-gray-900 sm:text-4xl sm:leading-none md:text-5xl lg:text-6xl dark:text-white text-center pb-4">
                                Aplikasi Administrator Data Simgos <br /> {hospitalName}
                            </h1>
                            <p className="mt-4 text-md text-gray-500 sm:mt-3 sm:text-2lg lg:text-3xl xl:text-4xl dark:text-gray-400 text-center">
                                Aplikasi ini dibuat sebagai pendukung SIMGOS Kementerian Kesehatan yang merupakan aplikasi utama dalam pelaksanaan pelayanan kesehatan di {hospitalName}.
                            </p>
                            <p className="mt-4 text-md text-gray-500 sm:mt-3 sm:text-2lg lg:text-3xl xl:text-4xl dark:text-gray-400 text-center">
                                Pada aplikasi ini, berisi menu-menu untuk melakukan monitoring data yang dianggap penting, dengan memanfaatkan database dari SIMGOS Kementerian Kesehatan.
                            </p>
                        </div>
                    </div>
                </div>
                <footer className="text-black/50  dark:text-gray-200 py-2">
                    <div className="text-center">
                        <p>&copy; {new Date().getFullYear()} Hidayat - Tim IT RSUD Dr. M. M. Dunda Limboto.</p>
                    </div>
                </footer>
            </div>
        </>
    );
}
