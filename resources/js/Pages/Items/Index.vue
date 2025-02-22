<template>
    <AppLayout title="Items">
        <Section
            width="100%"
            marginBottom="20px"
            marginTop="10px"
        >
            <template #title>
                <h1 class="text-2xl font-bold">Lista de Items</h1>
            </template>

            <template #content>
                <!-- Ajustar el layout para el input de búsqueda y botón de crear -->
                <div class="flex flex-col md:flex-row md:justify-between gap-4">
                    <TextInput
                        v-model="params.search"
                        placeholder="Buscar por Nombre"
                        class="w-full md:w-1/3"
                    />
                    <!-- Botón de crear -->
                    <button
                        @click="openCreateForm"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded inline-flex items-center transition duration-300 ease-in-out justify-center md:ml-4"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-earmark-plus mr-2" viewBox="0 0 16 16">
                            <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5"/>
                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                        </svg>
                        Crear
                    </button>
                </div>
                <div class="table-responsive">
                    <!-- Tabla -->
                    <vue3-datatable
                        :rows="props.items"
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
                                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded inline-flex items-center transition duration-300 ease-in-out" @click="editData(data.value)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                    </svg>
                                </button>
                                <button type="button" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded inline-flex items-center transition duration-300 ease-in-out" @click="deleteData(data.value)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                    </svg>
                                </button>
                                <!-- replicate -->
                                <!-- <button type="button" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded inline-flex items-center transition duration-300 ease-in-out" @click="replicateData(data.value)">
                                    R
                                </button> -->
                            </div>
                        </template>
                    </vue3-datatable>
                </div>
            </template>
        </Section>

        <Create :open="isSideMenuOpen" @close="closeCreateForm" :categories="categories"/>
        <Edit :open="openEdit" @close="closeEditForm" :data="handleData" :categories="categories"/>


        <Modal :show="isOpenDelete" @close="closeModalDelete">
            <div class="p-6">
                <h2 v-if="item.del" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Estás seguro de que deseas eliminar {{ item.name }}?
                </h2>
                <h2 v-else class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    No se puede eliminar este Item
                </h2>

                <p v-if="item.del" class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez que se elimine este Item, todos sus recursos y datos se eliminarán de forma permanente.
                </p>
                <p v-else class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Este Item ya tiene datos asociados, no se puede eliminar.
                </p>
                <div class="mt-6 flex justify-end gap-4">
                    <button class="underline font-medium text-red-500" @click="closeModalDelete"> Cancel </button>
                    <button
                        class="bg-red-500 hover:bg-red-600 text-white font-medium rounded-[6px] px-4 py-2 disabled:opacity-50"
                        type="submit"
                        :disabled="item.del == 0"
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
import Create from './Components/Create.vue';
import Edit from './Components/Edit.vue';
import Vue3Datatable from '@bhplugin/vue3-datatable'
import '@bhplugin/vue3-datatable/dist/style.css'
import { ref, defineProps, reactive } from 'vue'
import { useForm } from '@inertiajs/vue3';
import { useToastAlert } from '@/Composables/useToastAlert';

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
    categories: {
        type: Array,
        required: true,
    },
});

const cols = ref([
    { field: 'name', title: 'Nombre' },
    { field: 'category_name', title: 'Categoría' },
    { field: 'price', title: 'Precio' },
    { field: 'created_at', title: 'Fecha de Creación', hide : true },
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

const toast = useToastAlert();

const form = useForm({
    id: '',
});

const item = ref({
    name : '',
    del : false,
});

const isSideMenuOpen = ref(false);
const openEdit = ref(false);
const isOpenDelete = ref(false);

const handleData = ref({});

const openCreateForm = () => {
    isSideMenuOpen.value = true;
};

const closeCreateForm = () => {
    isSideMenuOpen.value = false;
};

const closeEditForm = () => {
    openEdit.value = false;
};

const editData = (data) => {
    handleData.value = data;
    openEdit.value = true;
};

const deleteData = (data) => {
    form.id = data.id;
    item.value.name = data.name;
    item.value.del = data.del;
    isOpenDelete.value = true;
};

const closeModalDelete = () => {
    isOpenDelete.value = false;
};

const handleDeleteData = () => {
    form.put(route('items.updateStatus', form.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModalDelete();
            toast.warningToast("Se ha eliminado correctamente", { position: "top-right" });
        },
    });
};

const replicateData = (data) => {
    form.post(route('items.replicate', data.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModalDelete();
            toast.warningToast("Se ha Replicado Correctamente", { position: "top-right" });
        },
    });
};

</script>
<style scoped>
.table-responsive {
        @apply overflow-auto rounded-lg border border-collapse;
    }</style>


