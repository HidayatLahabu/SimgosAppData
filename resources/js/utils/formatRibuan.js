export function formatRibuan(value) {
    if (!value && value !== 0 || isNaN(value)) {
        return "0";
    }

    const formattedValue = new Intl.NumberFormat('id-ID').format(Math.abs(value));
    return value < 0 ? `(${formattedValue})` : formattedValue;
}
