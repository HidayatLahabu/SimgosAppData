import { Link } from "@inertiajs/react";

export default function ButtonBpjs({ href, text = 'BPJS' }) {
    return (
        <Link href={href} className="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-2 px-3 rounded-lg">
            {text}
        </Link>
    );
}
