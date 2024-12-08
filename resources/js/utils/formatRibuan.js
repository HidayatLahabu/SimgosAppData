export function formatRibuan(value) {
    const formattedValue = new Intl.NumberFormat('id-ID').format(Math.abs(value));
    return value < 0 ? `(${formattedValue})` : formattedValue;
}
