<template>
    <div class="d-flex flex-column gap-4">
      <TitlePage title="Customer Order" />
      
      <!-- Customer Order Form -->
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <form @submit.prevent="" class="row g-3">
            <div class="col-md-3">
              <label for="pelanggan">Pelanggan</label>
              <n-select :options="options" />
            </div>
            <div class="col-md-3">
              <label for="label">Label</label>
              <n-select :options="options" />
            </div>
            <div class="col-md-3">
              <label for="no-co">No CO</label>
              <n-select :options="options" />
            </div>
            <div class="col-md-3">
              <label for="sales">Sales</label>
              <n-select :options="options" />
            </div>
            <div class="col-md-4">
              <label for="tgl-pembuatan">Tanggal pembuatan CO</label>
              <n-date-picker v-model:value="timestamp" type="date" />
            </div>
            <div class="col-md-4">
              <label for="termin">Termin</label>
              <n-select :options="options" />
            </div>
            <div class="col-md-4">
              <label for="tgl-jatuh-tempo">Tanggal jatuh tempo</label>
              <n-date-picker v-model:value="timestamp" type="date" />
            </div>
          </form>
        </div>
      </div>
  
      <!-- Detail Produk -->
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <span class="fw-medium fs-5">Detail Produk</span>
          <form @submit.prevent="" class="row g-3 mt-3">
            <div class="col-md-4">
              <label for="produk">Pelanggan</label>
              <n-input />
            </div>
            <div class="col-md-4">
              <label for="label">Label</label>
              <n-input />
            </div>
            <div class="col-md-4">
              <label for="no-co">No CO</label>
              <n-input />
            </div>
            <div class="col-md-4">
              <label for="sales">Sales</label>
              <n-input />
            </div>
            <div class="col-md-4">
              <label for="tgl-pembuatan">Tanggal pembuatan CO</label>
              <n-date-picker v-model:value="timestamp" type="date" />
            </div>
            <div class="col-md-4">
              <label for="termin">Termin</label>
              <n-input />
            </div>
          </form>
          <n-button attr-type="submit" type="primary" class="mt-3 ms-auto">Tambah produk</n-button>
        </div>
      </div>
  
      <!-- Daftar Produk -->
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <span class="fs-5 fw-medium">Daftar Produk</span>
          <n-data-table
            :columns="columns"
            :data="data"
            :pagination="pagination"
            :bordered="false"
            size="small"
            pagination-behavior-on-filter="first"
          />
        </div>
      </div>
  
      <!-- Ringkasan Harga -->
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex justify-content-between py-2">
            <span>Sub Total</span>
            <span>Rp.100.000</span>
          </div>
          <div class="d-flex justify-content-between py-2">
            <span>Discount</span>
            <span>Rp.50.000</span>
          </div>
          <div class="d-flex justify-content-between py-2">
            <span>PPN 11%</span>
            <span>Rp.150.000</span>
          </div>
          <div class="d-flex justify-content-between py-2 fw-bold border-top">
            <span>Total harga</span>
            <span>Rp 1.150.000</span>
          </div>
          <div class="d-flex justify-content-between py-2 border-top fw-semibold text-body-tertiary">
            <span>Term of payment</span>
            <span>14 Hari</span>
          </div>
          <div class="d-flex justify-content-between py-2 fw-semibold text-body-tertiary">
            <span>Due date</span>
            <span>17 Agustus 2024</span>
          </div>
        </div>
      </div>
  
      <n-button attr-type="submit" type="primary" class="mb-5" @click="handleSubmit">Submit</n-button>
    </div>
  </template>
  

<script lang="ts">
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import { defineComponent, ref, h } from 'vue';
import { ProductCustomerList } from '../../types/dto';
import Swal from 'sweetalert2';
import TitlePage from '../../Components/TitlePage.vue';

const data: ProductCustomerList[] = [
    { product_name: "Sania Premium Margarine", amount: 10, unit: "Karton", price: "Rp 200.000", discount: 1000, net: 1000, total: 1000 },
    { product_name: "Sania Premium Margarine", amount: 10, unit: "Karton", price: "Rp 200.000", discount: 1000, net: 1000, total: 1000 },
    { product_name: "Sania Premium Margarine", amount: 10, unit: "Karton", price: "Rp 200.000", discount: 1000, net: 1000, total: 1000 },
    { product_name: "Sania Premium Margarine", amount: 10, unit: "Karton", price: "Rp 200.000", discount: 1000, net: 1000, total: 1000 },
    { product_name: "Sania Premium Margarine", amount: 10, unit: "Karton", price: "Rp 200.000", discount: 1000, net: 1000, total: 1000 }
]

export default defineComponent({
    setup() {
        const notification = useNotification();

        function createColumns(): DataTableColumns<ProductCustomerList> {
            return [
                {
                    title: 'No',
                    key: 'no',
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: 'Product',
                    key: 'product_name'
                },
                {
                    title: 'Jumlah',
                    key: 'amount'
                },
                {
                    title: 'Satuan',
                    key: 'unit'
                },
                {
                    title: 'Harga',
                    key: 'price'
                },
                {
                    title: 'Discount',
                    key: 'discount'
                },
                {
                    title: 'Net',
                    key: 'net'
                },
                {
                    title: 'total',
                    key: 'total'
                },
                {
                    title: 'Action',
                    key: 'actions',
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: 'error',
                                size: 'small',
                                onClick: () => {
                                    Swal.fire({
                                        icon: 'question',
                                        text: `Delete ${row.product_name}?`,
                                        showCancelButton: true,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            notification.success({
                                                title: `${row.product_name} has deleted!`,
                                                closable: true,
                                                keepAliveOnHover: false,
                                                duration: 2000,
                                            });
                                        }
                                    })
                                }
                            },
                            { default: () => 'Delete' }
                        )
                    }
                }
            ]
        }

        function handleSubmit() {
            Swal.fire({
                icon: 'success',
                title: 'Success submitting'
            });
        }
        return {
            timestamp: ref(1183135260000),
            data,
            handleSubmit,
            columns: createColumns(),
            pagination: { pageSize: 10 },
            options: [
                {
                    label: 'Everybody\'s Got Something to Hide Except Me and My Monkey',
                    value: 'song0',
                    disabled: true
                },
                {
                    label: 'Drive My Car',
                    value: 'song1'
                },
                {
                    label: 'Norwegian Wood',
                    value: 'song2'
                },
            ]
        }
    },
    components: {
        TitlePage
    }
});
</script>

<style scoped></style>