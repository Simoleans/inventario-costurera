<template>
    <AppLayout title="Bitácora">
        <Section
            width="100%"
            marginBottom="20px"
            marginTop="10px"
        >
            <template #title>
                <h1 class="text-2xl font-bold">Bitácoras</h1>
            </template>

            <template #content>
                <!-- Botón de crear -->
                <div class="flex justify-end mb-6">
                    <Link
                        :href="route('data.create')"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded inline-flex items-center transition duration-300 ease-in-out"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-earmark-plus mr-2" viewBox="0 0 16 16">
                            <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5"/>
                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                        </svg>
                        Crear
                    </Link>
                </div>

                <!-- Sección de filtros con borde -->
                <div class="p-4 border border-gray-300 rounded-md mb-6">
                    <div class="flex flex-col md:flex-row md:justify-between gap-4">
                        <!-- Filtro de texto -->
                        <div class="w-full md:w-1/3">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                Filtrar por Item|Costurero:
                            </label>
                            <TextInput
                                v-model="params.search"
                                id="search"
                                placeholder="Buscar por Item|Costurero"
                                class="w-full"
                                type="text"
                            />
                        </div>

                        <!-- Filtro de fechas -->
                        <div class="flex flex-col md:flex-row gap-2 w-full md:w-2/3">
                            <!-- Filtro de fecha: desde -->
                            <div class="w-full md:w-1/2">
                                <label for="fromDate" class="block text-sm font-medium text-gray-700 mb-2">
                                    Desde:
                                </label>
                                <TextInput
                                    v-model="fromDate"
                                    id="fromDate"
                                    class="w-full"
                                    type="date"
                                    @change="handleFromDateChange"
                                />
                            </div>
                            <!-- Filtro de fecha: hasta -->
                            <div class="w-full md:w-1/2">
                                <label for="toDate" class="block text-sm font-medium text-gray-700 mb-2">
                                    Hasta:
                                </label>
                                <TextInput
                                    v-model="toDate"
                                    id="toDate"
                                    class="w-full"
                                    type="date"
                                    :min="fromDate"
                                    :disabled="!fromDate"
                                    @change="handleDateChange"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Botón de reiniciar filtros -->
                    <div class="flex justify-end mt-4">
                        <button
                            @click="resetFilters"
                            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded inline-flex items-center transition duration-300 ease-in-out"
                        >
                            Reiniciar filtros
                        </button>
                    </div>
                </div>

                <!-- Tabla -->
                <vue3-datatable
                    :rows="props.data"
                    :columns="cols"
                    :loading="loading"
                    :totalRows="total_rows"
                    :pageSize="params.pagesize"
                    :search="params.search"
                    :sortable="true"
                    :sortColumn="params.sort_column"
                    :sortDirection="params.sort_direction"
                    :noDataContent="'No hay datos registrados'"
                >
                    <template #actions="data">
                        <div class="flex gap-4">
                            <Link :href="route('data.edit',data.value.id)" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded inline-flex items-center transition duration-300 ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                            </Link>
                            <button type="button" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded inline-flex items-center transition duration-300 ease-in-out" @click="deleteData(data.value)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </vue3-datatable>
            </template>
        </Section>

        <Modal :show="isOpenDelete" @close="closeModalDelete">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Estás seguro de que deseas eliminar este registro?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez que se elimine el registro, todos sus recursos y datos se eliminarán de forma permanente.
                </p>
                <div class="mt-6 flex justify-end gap-4">
                    <button class="underline font-medium text-red-500" @click="closeModalDelete"> Cancel </button>
                    <button
                        class="bg-red-500 hover:bg-red-600 text-white font-medium rounded-[6px] px-4 py-2 disabled:opacity-50"
                        type="submit"
                        @click="handleDeleteData"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Section from '@/Components/Section.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import Vue3Datatable from '@bhplugin/vue3-datatable'
import '@bhplugin/vue3-datatable/dist/style.css'
import { ref, defineProps, reactive } from 'vue'
import { useForm } from '@inertiajs/vue3';
import { useToastAlert } from '@/Composables/useToastAlert';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    fromDate: {
        type: String,
        required: true,
    },
    toDate: {
        type: String,
        required: true,
    },

});

const cols = ref([
    { field: 'date', title: 'Fecha' },
    { field: 'qty', title: 'Total Items' },
    { field: 'customer_name', title: 'Cliente' },
    { field: 'total_amount', title: 'Total Precio' },
    { field: 'item_name', title: 'Item' },
    { field: 'employe_name', title: 'Costurero' },
    { field: 'actions', title: 'Acciones' },
]);

const params = reactive({
    current_page: 1,
    pagesize: 10,
    search: '',
    column_filters: [],
    sort_column: 'created_at',
    sort_direction: 'desc',
});

// Variables para el filtro de fechas
const fromDate = ref(props.fromDate)
const toDate = ref(props.toDate)

const toast = useToastAlert();

const form = useForm({
    id: '',
});

const isOpenDelete = ref(false);

// Lógica para manejar el cambio de fecha en "Desde"
const handleFromDateChange = () => {
    if (fromDate.value) {
        toDate.value = ''; // Reinicia el campo "Hasta" al cambiar "Desde"
    }
    handleDateChange();
};

// Lógica para aplicar los filtros de fechas
const handleDateChange = () => {
    const queryParams = {};

    if (fromDate.value) queryParams.fromDate = fromDate.value;
    if (toDate.value) queryParams.toDate = toDate.value;

    router.get(route('data.index'), queryParams, {
        preserveScroll: true,
        replace: true
    });
};

// Función para reiniciar los filtros
const resetFilters = () => {
    params.search = '';
    fromDate.value = '';
    toDate.value = '';
    router.get(route('data.index'), {}, {
        preserveScroll: true,
        replace: true
    });
};

const deleteData = (data) => {
    form.id = data.id;
    isOpenDelete.value = true;
};

const closeModalDelete = () => {
    isOpenDelete.value = false;
};

const handleDeleteData = () => {
    form.put(route('data.updateStatus', form.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModalDelete();
            toast.warningToast("Se ha eliminado correctamente", { position: "top-right" });
        },
    });
};
</script>
