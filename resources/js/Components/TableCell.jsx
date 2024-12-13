// TableCell.jsx
export default function TableCell({ children, className = "" }) {
    // Fungsi untuk menghapus tag HTML dari teks
    const cleanText = (text) => {
        if (typeof text === 'string') {
            return text.replace(/<[^>]+>/g, '');  // Hapus tag HTML
        }
        return text;
    };

    return (
        <td className={`px-3 py-3 border border-gray-500 dark:border-gray-600 ${className}`}>
            {/* Terapkan fungsi cleanText jika children adalah string */}
            {cleanText(children)}
        </td>
    );
}
