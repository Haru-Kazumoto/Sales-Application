<template>
    <div>
        <ApexCharts type="bar" :options="chartOptions" :series="series" height="350" />
    </div>
</template>

<script setup>
import { ref, defineProps, computed } from 'vue';
import ApexCharts from 'vue3-apexcharts';
import { formatRupiah } from '../Utils/options-input.utils';
import { usePage } from '@inertiajs/vue3';

// Define props
const props = defineProps({
    target: Array,
    total_target: [],
});

// Data series (reactive with computed)
const series = computed(() => {

    return [
        {
            name: 'Total Penjualan',
            data: props.total_target
        },
        {
            name: 'Target Penjualan',
            data: props.target.map((data) => data.monthly_target) // Target untuk 12 bulan
        },
    ];
});

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
                // Format angka ke Rupiah dengan toLocaleString
                return val.toLocaleString('id-ID');
            }
        }
    },
    yaxis: {
        title: {
            text: 'Rp (RUPIAH)'
        },
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                // Format angka ke Rupiah dengan toLocaleString
                return "Rp " + val.toLocaleString('id-ID');
            }
        }
    },
    colors: ['#00a54f', '#FFFF00']
});

</script>

<style scoped>
/* Tambahkan gaya khusus jika diperlukan */
</style>