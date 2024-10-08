<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-4">
            <!-- Sales Cards Row -->
            <div class="row ">
                <div class="col-12">
                    <div class="card shadow-sm border border-secondary-subtle ps-2 text-white bg-opacity-75 flex-wrap mt-3"
                        style="background-color: #00a54f;">
                        <div class="card-body d-flex align-items-center">
                            <!-- <div class="row">

                            </div> -->
                            <div class="d-flex flex-column gap-3">
                                <span class="fs-3 fw-semibold">Welcome to the panel admin,
                                    {{ $page.props.auth.user.fullname }}!</span>
                                <span class="fs-5">What we want to do today?</span>
                            </div>
                            <div class="d-flex ms-auto">
                                <n-image width="300" src="/images/admin-img.png" preview-disabled
                                    class="img-fluid" style="padding-left: 50px;" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="row mb-3 ">
                <div class="col-12 col-lg-5">
                    <div class="card shadow h-100" style=" border: none;">
                        <div class="card-body d-flex flex-column gap-2">
                            <span class="fs-5 fw-medium">New Customer</span>
                            <n-data-table />
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="card shadow flex-grow-1 h-100" style="border: none;">
                        <div class="card-body d-flex flex-column" >
                            <span class="fs-5 fw-medium">Sales Target</span>
                            <n-data-table />
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</template>


<script lang="ts">
import { defineComponent, ref } from 'vue';
import SalesCountCard from '../../Components/SalesCountCard.vue';
import ChartSales from '../../Components/ChartSales.vue';
import ChartSalesTarget from '../../Components/ChartSalesTarget.vue';
import TitlePage from '../../Components/TitlePage.vue';
import { usePage } from '@inertiajs/vue3';

export default defineComponent({
    setup() {
        const page = usePage();

        return {
            timestamp: ref(1183135260000)
        }
    },
    components: {
        SalesCountCard,
        ChartSales,
        ChartSalesTarget,
        TitlePage
    }
});
</script>

<style scoped></style>