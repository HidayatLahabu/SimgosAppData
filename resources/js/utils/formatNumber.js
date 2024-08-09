export function formatNumber(value) {
    const formattedValue = new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(Math.abs(value));
    return value < 0 ? `(${formattedValue})` : formattedValue;
}
