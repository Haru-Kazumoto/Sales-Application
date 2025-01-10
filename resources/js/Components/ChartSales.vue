<template>
    <div>
        <ApexCharts type="bar" :options="chartOptions" :series="series" height="350" />
    </div>
</template>

<script setup>
import { ref, defineProps, computed } from 'vue';
import ApexCharts from 'vue3-apexcharts';
import { formatRupiah } from '../Utils/options-input.utils';

// Define props
const props = defineProps({
    target: Number
});

// Data series (reactive with computed)
const series = computed(() => [{
    name: 'Total penjualan',
    data: [7600000, 8500000, 101000, 98000, 870000, 1050000, 910000, 1140000, 9400000]
}, {
    name: 'Target penjualan',
    data: Array(12).fill(props.target) // Menggunakan target prop untuk semua nilai
}]);

const chartOptions = ref({
    chart: {
        type: 'bar',
        height: 450,
        toolbar: {
            show: true,
            tools: {
                download: false
            }
        }
    },
    plotOptions: {
        bar: {
            horizontal: true,
            dataLabels: {
                position: 'top',
            },
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        labels: {
            formatter: function (val) {
                if (val >= 1000000000) {
                    return "Rp " + (val / 1000000000) + " M";
                } else if (val >= 1000000) {
                    return "Rp " + (val / 1000000) + " Jt";
                }
                return "Rp " + val.toLocaleString();
            }
        }
    },
    yaxis: {
        title: {
            text: 'Rp (RUPIAH)'
        },
        // labels: {
        //     formatter: function (val) {
        //         if (val >= 1000000000) {
        //             return "Rp " + (val / 1000000000) + " M";
        //         } else if (val >= 1000000) {
        //             return "Rp " + (val / 1000000) + " Jt";
        //         }
        //         return "Rp " + val.toLocaleString();
        //     }
        // }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                if (val >= 1000000000) {
                    return "Rp " + (val / 1000000000).toFixed(2) + " M";
                } else if (val >= 1000000) {
                    return "Rp " + (val / 1000000) + " Jt";
                }
                return "Rp " + val.toLocaleString();
            }
        }
    },
    colors: ['#00a54f', '#FFFF00']
});

</script>

<style scoped>
/* Tambahkan gaya khusus jika diperlukan */
</style>