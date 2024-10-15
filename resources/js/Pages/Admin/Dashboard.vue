<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-4">
            <!-- Sales Cards Row -->
            <div class="row ">
                <div class="col-12">
                    <div class="card shadow-sm border border-secondary-subtle ps-2 text-white bg-opacity-75 flex-wrap mt-3"
                        style="background-color: #00a54f;">
                        <div class="card-body d-flex align-items-center">
                            <n-image width="300" src="/images/admin-img2.png" preview-disabled class="img-fluid" />
                            <div class="d-flex flex-column">
                                <span class="fs-3 fw-semibold">Selamat datang di panel admin!</span>
                                <span class="fs-5 mt-2">
                                    Siap mengelola tugas Anda dan mengambil kendali? Mari jadikan hari ini produktif!
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <!-- First row -->
                <div class="col-12 col-md-6 col-lg-4 ">
                    <div class="card border-0 shadow-sm d-flex flex-column h-100">
                        <div class="card-body">
                            <div class="card-title d-flex gap-3 align-items-center">
                                <n-icon-wrapper :size="40" :border-radius="10" color="#024CAA">
                                    <n-icon :size="18" :component="Person" color="white" />
                                </n-icon-wrapper>
                                <Link :href="route('admin.user-management')" class="ms-auto">
                                <n-button type="default" ghost>Direct</n-button>
                                </Link>
                            </div>
                            <div class="d-flex flex-column">
                                <h5 class="fw-bold">User</h5>
                                <span>Menambah, mengurangi, dan memantau pengguna dari aplikasi penjualan</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 ">
                    <div class="card border-0 shadow-sm d-flex flex-column h-100">
                        <div class="card-body">
                            <div class="card-title d-flex gap-3 align-items-center">
                                <n-icon-wrapper :size="40" :border-radius="10" color="#FF6500">
                                    <n-icon :size="25" :component="AdminPanelSettingsFilled" color="white" />
                                </n-icon-wrapper>
                                <Link :href="route('admin.user-management')" class="ms-auto">
                                <n-button type="default" ghost>Direct</n-button>
                                </Link>
                            </div>
                            <div class="d-flex flex-column">
                                <h5 class="fw-bold">Role</h5>
                                <span>Memberikan peran kepada pengguna bagi siapa saja yang dapat mengakses suatu
                                    halaman</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 ">
                    <div class="card border-0 shadow-sm d-flex flex-column h-100">
                        <div class="card-body">
                            <div class="card-title d-flex gap-3 align-items-center">
                                <n-icon-wrapper :size="40" :border-radius="10" color="#347928">
                                    <n-icon :size="25" :component="GitNetworkSharp" color="white" />
                                </n-icon-wrapper>
                                <Link :href="route('admin.user-management')" class="ms-auto">
                                <n-button type="default" ghost>Direct</n-button>
                                </Link>
                            </div>
                            <div class="d-flex flex-column">
                                <h5 class="fw-bold">Division</h5>
                                <span>Memberikan divisi kepada seorang pengguna sehingga Anda dapat mengetahui divisi
                                    apa yang dimiliki pengguna tersebut</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second row -->
                <div class="col-12 col-md-6 col-lg-4 ">
                    <div class="card border-0 shadow-sm d-flex flex-column h-100">
                        <div class="card-body">
                            <div class="card-title d-flex gap-3 align-items-center">
                                <n-icon-wrapper :size="40" :border-radius="10" color="#B8001F">
                                    <n-icon :size="20" :component="Settings" color="white" />
                                </n-icon-wrapper>
                                <Link :href="route('admin.user-management')" class="ms-auto">
                                <n-button type="default" ghost>Direct</n-button>
                                </Link>
                            </div>
                            <div class="d-flex flex-column">
                                <h5 class="fw-bold">Access Menu</h5>
                                <span>Mengatur siapa saja yang memiliki hak akses untuk suatu halaman yang ada di
                                    aplikasi sales</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 ">
                    <div class="card border-0 shadow-sm d-flex flex-column h-100">
                        <div class="card-body">
                            <div class="card-title d-flex gap-3 align-items-center">
                                <n-icon-wrapper :size="40" :border-radius="10" color="#FABC3F">
                                    <n-icon :size="20" :component="People" color="white" />
                                </n-icon-wrapper>
                                <Link :href="route('admin.user-management')" class="ms-auto">
                                <n-button type="default" ghost>Direct</n-button>
                                </Link>
                            </div>
                            <div class="d-flex flex-column">
                                <h5 class="fw-bold">Customer</h5>
                                <span>Menambahkan, mengurangi, dan mengedit data master pelanggan</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 ">
                    <div class="card border-0 shadow-sm d-flex flex-column h-100">
                        <div class="card-body">
                            <div class="card-title d-flex gap-3 align-items-center">
                                <n-icon-wrapper :size="40" :border-radius="10" color="#FF9100">
                                    <n-icon :size="20" :component="Box24Filled" color="white" />
                                </n-icon-wrapper>
                                <Link :href="route('admin.user-management')" class="ms-auto">
                                <n-button type="default" ghost>Direct</n-button>
                                </Link>
                            </div>
                            <div class="d-flex flex-column">
                                <h5 class="fw-bold">Supplier</h5>
                                <span>Menambahkan, mengurangi, dan mengedit data master pemasok</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>


<script lang="ts">
import { defineComponent, ref } from 'vue';
import SalesCountCard from '../../Components/SalesCountCard.vue';
import ChartSales from '../../Components/ChartSales.vue';
import ChartSalesTarget from '../../Components/ChartSalesTarget.vue';
import TitlePage from '../../Components/TitlePage.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Person, GitNetworkSharp, Settings, People } from "@vicons/ionicons5";
import { AdminPanelSettingsFilled } from "@vicons/material";
import { Box24Filled } from "@vicons/fluent";

export default defineComponent({
    setup() {
        const page = usePage();

        return {
            timestamp: ref(1183135260000),
            Person,
            AdminPanelSettingsFilled,
            GitNetworkSharp,
            Settings,
            People,
            Box24Filled
        }
    },
    components: {
        SalesCountCard,
        ChartSales,
        ChartSalesTarget,
        TitlePage,
        Link
    }
});
</script>

<style scoped></style>