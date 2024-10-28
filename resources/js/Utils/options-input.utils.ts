import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

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

export const formatDate = (date: any) => {
    return dayjs(date).format('dddd, D MMMM YYYY HH:mm');
};

export const capitalize = (value): string => {
    return value.toUpperCase();
}

export const onlyNumber = (value) => {
    return value.replace(/\D/g, '');
}