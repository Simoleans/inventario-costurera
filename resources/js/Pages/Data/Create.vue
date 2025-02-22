<template>
    <AppLayout title="Datos">
        <Section
            width="100%"
            marginBottom="20px"
            marginTop="10px"
        >
            <template #title>
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">Registrar Bitácora</h1>
                    <!-- Input de Fecha Editable -->
                    <input
                        type="date"
                        v-model="form.date"
                        class="border border-gray-300 p-2 rounded"
                    />
                </div>
            </template>

            <template #content>
                <!-- Secciones dinámicas -->
                <div v-for="(section, index) in form.sections" :key="section.id" class="border p-4 mb-1 rounded-lg relative w-full">
                    <div class="flex gap-2 w-full flex-wrap">
                        <!-- Select Cliente (customer) obligatorio -->
                        <select v-model="section.customer_id" class="border p-2 rounded flex-grow">
                            <option disabled value="">Seleccione cliente</option>
                            <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name_complete }}</option>
                        </select>

                        <!-- Select Costurero (employe) con datos desde props.employes -->
                        <select v-model="section.employe_id" class="border p-2 rounded flex-grow">
                            <option disabled value="">Seleccione costurero</option>
                            <option v-for="employe in employes" :key="employe.id" :value="employe.id">{{ employe.name }}</option>
                        </select>

                        <!-- Input de Pieza/Item con búsqueda en tiempo real -->
                        <div class="relative flex-grow">
                            <input
                                type="text"
                                v-model="section.searchQuery"
                                @input="searchItems(section)"
                                placeholder="Buscar pieza/item"
                                class="border p-2 rounded w-full"
                            />
                            <ul v-if="section.searchResults.length" class="absolute z-10 bg-gray-200 border-2 border-gray-500 w-full max-h-40 overflow-auto">
                                <li v-for="item in section.searchResults" :key="item.id" @click="selectItem(section, item)" class="p-2 hover:bg-gray-100 cursor-pointer">
                                    {{ item.name }} ({{ item.category.name }})
                                </li>
                            </ul>
                        </div>

                        <!-- Input Number con botones -->
                        <div class="flex items-center">
                            <button @click="decrement(section)" :disabled="section.qty <= 0" class="bg-gray-300 p-2 rounded-l">-</button>
                            <input
                                type="number"
                                v-model="section.qty"
                                min="0"
                                class="border p-2 w-16 text-center"
                                readonly
                            />
                            <button @click="increment(section)" class="bg-gray-300 p-2 rounded-r">+</button>
                        </div>
                    </div>

                    <!-- Botón para eliminar la sección (a partir de la segunda) -->
                    <div class="flex mt-2 justify-end" v-if="index !== 0">
                        <button class="underline font-medium text-red-500 cursor-pointer" @click="removeSection(index)">
                            Eliminar
                        </button>
                    </div>
                </div>

                <!-- Botón para agregar nueva sección -->
                <div class="flex justify-start p-4">
                    <button @click="addSection" class="bg-green-500 text-white rounded-full p-2 flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                            <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5"/>
                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                        </svg>
                    </button>
                </div>

                <!-- Botón de Registrar -->
                <div class="flex justify-end gap-3">
                    <Link :href="route('data.index')" class="bg-gray-300 text-black p-3 rounded-lg text-lg">
                        Volver
                    </Link>
                    <button :disabled="isFormInvalid" @click="submitForm" class="bg-blue-500 text-white p-4 rounded-lg text-lg" :class="{'opacity-50 cursor-not-allowed': isFormInvalid}">
                        Registrar
                    </button>
                </div>

            </template>
        </Section>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Section from '@/Components/Section.vue';
import { useToastAlert } from '@/Composables/useToastAlert';
import { Link } from '@inertiajs/vue3';

const toast = useToastAlert();

// Definir props
const props = defineProps({
    employes: {
        type: Array,
        required: true,
    },
    customers: {
        type: Array,
        required: true,
    },
});

// Formulario reactivo
const form = useForm({
    date: new Date().toISOString().substr(0, 10), // Fecha actual
    sections: [
        { id: Date.now(), customer_id: '', employe_id: '', items_id: '', qty: 0, searchQuery: '', searchResults: [] }
    ]
});

// Agregar sección
const addSection = () => {
    form.sections.push({ id: Date.now(), customer_id: '', employe_id: '', items_id: '', qty: 0, searchQuery: '', searchResults: [] });
};

// Eliminar sección (solo a partir de la segunda)
const removeSection = (index) => {
    if (index > 0) {
        form.sections.splice(index, 1);
    }
};

// Incrementar cantidad
const increment = (section) => {
    section.qty++;
};

// Decrementar cantidad
const decrement = (section) => {
    if (section.qty > 0) section.qty--;
};

// Buscar piezas en tiempo real
const searchItems = async (section) => {
    if (section.searchQuery.length >= 2) {
        try {
            const response = await axios.post(route('items.searchName'), {
                params: { name: section.searchQuery }
            });
            section.searchResults = response.data.items;
        } catch (error) {
            console.error('Error buscando piezas:', error);
        }
    } else {
        section.searchResults = [];
    }
};

// Seleccionar pieza de la búsqueda
const selectItem = (section, item) => {
    section.items_id = item.id; // Guardar el ID del ítem
    section.searchQuery = item.name; // Reflejar el nombre en el input
    section.searchResults = []; // Limpiar resultados después de seleccionar
};

// Computed para verificar si el formulario tiene campos vacíos
const isFormInvalid = computed(() => {
    return form.sections.some(section => !section.customer_id || !section.employe_id || !section.items_id || section.qty <= 0);
});

// Enviar formulario
const submitForm = () => {
    form.post(route('data.store'), {
        onSuccess: () => {
            toast.warningToast("Se ha registrado correctamente", { position: "top-right" });
        }
    });
};

// Alertar sobre cambios no guardados al intentar salir
window.addEventListener('beforeunload', (e) => {
    if (form.isDirty) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>

<style scoped>
/* Estilos específicos de la página */
button[disabled] {
    opacity: 0.5;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .flex {
        flex-direction: column;
    }
}
</style>
