<template>
    <div>
        <ApexCharts type="bar" :options="chartOptions" :series="series" height="350" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import ApexCharts from 'vue3-apexcharts';

// Data series and chart options
const series = ref([{
    name: 'Total penjualan',
    data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
}, {
    name: 'Target penjualan',
    data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
}]);

const chartOptions = ref({
    chart: {
        type: 'bar',
        height: 350,
        toolbar: {
            show: true, // Untuk menampilkan toolbar
            tools: {
                download: false // Menonaktifkan opsi download
            }
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
        },
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
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    },
    yaxis: {
        title: {
            text: 'Rp (RUPIAH)'
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return "Rp " + val + " thousands"
            }
        }
    },
    colors: ['#00a54f', '#FFFF00']
});

</script>

<style scoped>
/* Tambahkan gaya khusus jika diperlukan */
</style>