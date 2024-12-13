import sanitizeHtml from 'sanitize-html';

export default function TableCell({ children, className = "" }) {
    // Function to clean HTML using sanitize-html
    const cleanText = (text) => {
        if (typeof text === 'string') {
            return sanitizeHtml(text);  // Use sanitizeHtml to clean the content
        }
        return text;
    };

    return (
        <td className={`px-3 py-3 border border-gray-500 dark:border-gray-600 ${className}`}>
            {cleanText(children)}  {/* Render sanitized content */}
        </td>
    );
}
