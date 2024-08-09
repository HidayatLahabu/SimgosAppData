import { Link } from "@inertiajs/react";

export default function Pagination({ links = [] }) {
    console.log('Pagination links received:', links);

    // Cek jika links adalah array
    if (!Array.isArray(links)) {
        return <div>No pagination links available.</div>;
    }

    return (
        <nav className="text-center mt-4">
            {links.length > 0 ? (
                links.map((link, index) => (
                    <Link
                        preserveScroll
                        href={link.url || ""}
                        key={index} // menggunakan index sebagai key jika label tidak unik
                        className={
                            "inline-block py-2 px-3 rounded-lg text-gray-200 text-xs " +
                            (link.active ? "bg-gray-950" : "hover:bg-gray-950") +
                            (!link.url ? " text-gray-500 cursor-not-allowed" : "")
                        }
                        dangerouslySetInnerHTML={{ __html: link.label }}
                    ></Link>
                ))
            ) : (
                <div>No pagination links available.</div>
            )}
        </nav>
    );
}
