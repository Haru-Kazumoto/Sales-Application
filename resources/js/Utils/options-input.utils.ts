export const alokasiOptions = [
    { label: "DNP", value: "DNP" },
    { label: "DKU", value: "DKU" }
];

export const formatRupiah = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0, // Remove decimal places
        maximumFractionDigits: 0
    }).format(amount);
};