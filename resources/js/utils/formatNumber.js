// export function formatNumber(value) {
//     const formattedValue = new Intl.NumberFormat('id-ID', {
//         minimumFractionDigits: 2,
//         maximumFractionDigits: 2
//     }).format(Math.abs(value));
//     return value < 0 ? `(${formattedValue})` : formattedValue;
// }

export function formatNumber(value) {
    const formattedValue = new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(Math.abs(value));

    return value < 0 ? `-${formattedValue}` : formattedValue;
}
