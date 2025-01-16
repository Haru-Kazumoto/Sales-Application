<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="SETTING TARGET SALESMAN" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('marketing.index-target'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>

        <div class="d-flex align-items-center justify-content-center">
            <div class="card shadow-sm border-0 w-75 mb-3">
                <div class="card-body">
                    <div class="card-title d-flex align-items-center w-100">
                        <span class="fs-5">Setting Target</span>
                        <!-- <div class="d-flex align-items-center ms-auto gap-2">
                            <span>Periode</span>
                            <n-date-picker v-model:formatted-value="targetForm.period" type="year" value-format="yyyy"
                                clearable style="flex-grow: 1; min-width: 150px;" placeholder="Tahun Periode"
                                @update:value="updateYear" />
                        </div> -->
                    </div>

                    <n-divider>Informasi Salesman</n-divider>

                    <div class="row g-2">
                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Salesman</label>
                            <n-input placeholder="" size="large" v-model:value="model.fullname" disabled />
                        </div>
                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Salesman UID</label>
                            <n-input placeholder="" size="large" v-model:value="model.saleman_uid" disabled />
                        </div>
                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Divisi</label>
                            <n-input placeholder="" size="large" v-model:value="model.division" disabled />
                        </div>

                        <n-divider>Informasi Target</n-divider>
                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Target Tahunan</label>
                            <n-input placeholder="" size="large" v-model:value="targetForm.annual_target"
                                @input="(value) => targetForm.annual_target = value.replace(/\D/g, '')">
                                <template #prefix>Rp</template>
                            </n-input>
                        </div>
                        <n-divider dashed></n-divider>
                        <div class="d-flex">
                            <n-button type="primary" class="ms-auto" @click="openAddModal">Buat Target
                                Bulanan</n-button>

                            <n-modal v-model:show="showModal" class="custom-card" preset="card" :style="bodyStyle"
                                title="Buat Target Bulanan" :bordered="false" size="huge" :segmented="segmented">
                                <div class="row g-2">
                                    <div class="d-flex flex-column gap-1 col-5">
                                        <label for="">BULAN</label>
                                        <n-select size="large" placeholder="" :options="months"
                                            v-model:value="target_model.month"></n-select>
                                    </div>
                                    <div class="d-flex flex-column gap-1 col-7">
                                        <label for="">TARGET</label>
                                        <n-input size="large" placeholder="" v-model:value="target_model.target"
                                            @input="(value) => target_model.target = value.replace(/\D/g, '')">
                                            <template #prefix>Rp</template>
                                        </n-input>
                                    </div>
                                </div>

                                <template #footer>
                                    <div class="d-flex">
                                        <div class="d-flex ms-auto gap-3">
                                            <n-button type="error" @click="closeModal">CANCEL</n-button>
                                            <n-button type="primary" @click="saveTarget">SAVE</n-button>
                                        </div>
                                    </div>
                                </template>
                            </n-modal>
                        </div>
                        <n-data-table :columns="columns" :data="targetForm.monthly_targets" />


                        <!-- <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Target Bulanan</label>
                            <n-input placeholder="" size="large" v-model:value="targetForm.monthly_target"
                                @input="(value) => targetForm.monthly_target = value.replace(/\D/g, '')">
                                <template #prefix>Rp</template>
                            </n-input>
                        </div> -->

                    </div>

                    <n-divider></n-divider>

                    <div class="d-flex">
                        <div class="d-flex gap-2 ms-auto">
                            <n-button type="primary" @click="handleSubmit">Simpan Informasi</n-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ArrowBack } from '@vicons/ionicons5';
import Swal from 'sweetalert2';
import { formatRupiah } from '../../Utils/options-input.utils';
import { h } from 'vue';
import { NButton } from 'naive-ui';

export default defineComponent({
    setup() {
        const page = usePage();
        const salesman = page.props.salesman as any;
        const showModal = ref(false);

        function createColumns() {
            return [
                {
                    title: "#",
                    key: "id",
                    width: 50,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "BULAN",
                    key: "month",
                    width: 200,
                    render(row) {
                        // Array untuk memetakan angka ke nama bulan
                        const months = [
                            "JANUARI", "FEBRUARI", "MARET", "APRIL", "MEI",
                            "JUNI", "JULI", "AGUSTUS", "SEPTEMBER", "OKTOBER",
                            "NOVEMBER", "DESEMBER"
                        ];

                        // Ambil nama bulan berdasarkan angka (row.month - 1 karena indeks array dimulai dari 0)
                        const monthName = months[row.month - 1];

                        // Return nama bulan (atau fallback jika datanya tidak valid)
                        return monthName;
                    }
                },
                {
                    title: "TARGET",
                    key: "target",
                    width: 150,
                    render(row) {
                        return formatRupiah(row.target);
                    }
                },
                {
                    title: "AKSI",
                    key: "action",
                    width: 100,
                    render(row, index) {
                        return h('div', { class: 'd-flex gap-2' }, [
                            h(NButton, {
                                type: "info",
                                size: "small",
                                onClick: () => {
                                    showModal.value = true;
                                    openEditModal(index);
                                }
                            }, { default: () => "EDIT" }),
                            h(NButton, {
                                type: "error",
                                size: "small",
                                onClick: () => {
                                    deleteTarget(index);
                                }
                            }, { default: () => "HAPUS" })
                        ]);
                    }
                }
            ]
        }

        const target_model = ref({
            target: null as unknown as number,
            month: null as unknown as number,
        });

        const model = ref({
            fullname: salesman.fullname,
            saleman_uid: salesman.user_uid,
            division: salesman.division.division_name,
        });

        const targetForm = useForm({
            annual_target: page.props.auth.user.annual_target,
            monthly_targets: page.props.targets.map(target => ({
                month: target.at_month,  // Menggunakan at_month atau bisa menggunakan bulan dari data
                target: target.monthly_target,  // Target per bulan
            }))
        });

        const editingIndex = ref(null);

        function openAddModal() {
            target_model.value = { month: null as unknown as number, target: null as unknown as number }; // Reset data form
            editingIndex.value = null; // Tambah data baru
            showModal.value = true; // Tampilkan modal
        }
        function openEditModal(index) {
            target_model.value = { ...targetForm.monthly_targets[index] }; // Ambil data untuk diedit
            editingIndex.value = index; // Simpan indeks data yang sedang diedit
            showModal.value = true; // Tampilkan modal
        }
        function deleteTarget(index) {
            targetForm.monthly_targets.splice(index, 1);
        }
        function saveTarget() {
            if (editingIndex.value === null) {
                // Tambah data baru
                targetForm.monthly_targets.push({ ...target_model.value });
            } else {
                targetForm.monthly_targets[editingIndex.value] = { ...target_model.value };
            }

            // Reset dan tutup modal
            closeModal();
        }
        function closeModal() {
            target_model.value = { month: null as unknown as number, target: null as unknown as number };
            editingIndex.value = null;
            showModal.value = false;
        }

        function handleAddMonthlyTarget() {
            targetForm.monthly_targets.push(target_model.value);

            target_model.value = {
                target: null as unknown as number,
                month: null as unknown as number,
            }

            showModal.value = false;
        }

        function handleEditMonthlyTarget(index) {
            // Perbarui data pada array `monthly_targets` berdasarkan indeks
            targetForm.monthly_targets[index] = { ...target_model.value };

            // Reset `target_model` setelah data disimpan
            target_model.value = {
                target: null as unknown as number,
                month: null as unknown as number,
            };

            // Sembunyikan modal setelah proses selesai
            showModal.value = false;
        }

        function handleSubmit() {
            targetForm.patch(route('marketing.update-target', salesman.id), {
                onSuccess: (page) => {
                    Swal.fire(page.props.flash.success, '', 'success');
                },
                onError: () => {
                    Swal.fire('Gagal memperbarui', '', 'error');
                }
            })
        }

        function updateYear(value) {
            // Pastikan `targetForm.period` hanya mendapatkan tahun dalam format string (misal: "2024")
            const year = new Date(value).getFullYear();
            targetForm.period = year;
        }

        const months = [
            {
                label: "JANUARI",
                value: 1
            },
            {
                label: "FEBRUARI",
                value: 2
            },
            {
                label: "MARET",
                value: 3
            },
            {
                label: "APRIL",
                value: 4
            },
            {
                label: "MEI",
                value: 5
            },
            {
                label: "JUNI",
                value: 6
            },
            {
                label: "JULI",
                value: 7
            },
            {
                label: "AGUSTUS",
                value: 8
            },
            {
                label: "SEPTEMBER",
                value: 9
            },
            {
                label: "OKTOBER",
                value: 10
            },
            {
                label: "NOVEMBER",
                value: 11
            },
            {
                label: "DESEMBER",
                value: 12
            }
        ];


        return {
            columns: createColumns(),
            handleSubmit,
            updateYear,
            router,
            salesman,
            months,
            model,
            targetForm,
            ArrowBack,
            showModal,
            bodyStyle: {
                width: '600px'
            },
            segmented: {
                content: 'soft',
                footer: 'soft'
            } as const,
            target_model,
            handleAddMonthlyTarget,
            handleEditMonthlyTarget,
            openAddModal,
            openEditModal,
            saveTarget,
            closeModal,
            deleteTarget
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>