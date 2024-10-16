export const alokasiOptions = [
    { label: "DNP", value: "DNP" },
    { label: "DKU", value: "DKU" }
];

export const formatRupiah = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2, // Remove decimal places
        maximumFractionDigits: 2
    }).format(amount);
};

export const capitalize = (value): string => {
    return value.toUpperCase();
}

export const onlyNumber = (value) => {
    return value.replace(/\D/g, '');
}