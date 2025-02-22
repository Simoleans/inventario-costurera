<template>
    <AppLayout title="Editar Datos">
        <Section
            width="100%"
            marginBottom="20px"
            marginTop="10px"
        >
            <template #title>
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">Editar Bitácora</h1>
                    <!-- Input de Fecha Editable -->
                </div>
            </template>

            <template #content>
                <!-- <pre>{{ props.register }}</pre> -->
                <!-- Select Cliente -->
                <div class="mb-4 w-full">
                    <label for="customer" class="block font-medium mb-2">Seleccionar Cliente</label>
                    <select v-model="form.customer_id" id="customer" class="border p-2 rounded w-full">
                        <option disabled value="">Seleccione cliente</option>
                        <option v-for="customer in customers" :key="customer.id" :value="customer.id" :selected="customer.id == props.register.client_id">{{ customer.name_complete }}</option>
                    </select>
                </div>

                <!-- Select Costurero (employe) -->
                <div class="flex gap-2 w-full flex-wrap">
                    <select v-model="form.employe_id" class="border p-2 rounded flex-grow">
                        <option disabled value="">Seleccione costurero</option>
                        <option v-for="employe in employes" :key="employe.id" :value="employe.id">{{ employe.name }}</option>
                    </select>

                    <!-- Input de Pieza/Item con búsqueda en tiempo real -->
                    <div class="relative flex-grow">
                        <input
                            type="text"
                            v-model="form.searchQuery"
                            @input="searchItems"
                            placeholder="Buscar pieza/item"
                            class="border p-2 rounded w-full"
                        />
                        <ul v-if="form.searchResults.length" class="absolute z-10 bg-gray-200 border-2 border-gray-500 w-full max-h-40 overflow-auto">
                            <li v-for="item in form.searchResults" :key="item.id" @click="selectItem(item)" class="p-2 hover:bg-gray-100 cursor-pointer">
                                {{ item.name }}
                            </li>
                        </ul>
                    </div>

                    <div class="flex items-center">
                        <button @click="decrement" :disabled="form.qty <= 0" class="bg-gray-300 p-2 rounded-l">-</button>
                        <input
                            type="number"
                            v-model="form.qty"
                            min="0"
                            class="border p-2 w-16 text-center"
                            readonly
                        />
                        <button @click="increment" class="bg-gray-300 p-2 rounded-r">+</button>
                    </div>
                </div>

                <div class="flex justify-end mt-4 gap-3">
                    <Link :href="route('data.index')" class="bg-gray-300 text-black p-3 rounded-lg text-lg">
                        Volver
                    </Link>
                    <button :disabled="isFormInvalid" @click="submitForm" class="bg-blue-500 text-white p-3 rounded-lg text-lg" :class="{'opacity-50 cursor-not-allowed': isFormInvalid}">
                        Actualizar
                    </button>
                </div>
            </template>
        </Section>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, computed, onMounted } from 'vue';
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
    register: { // Data cargada desde el backend para editar
        type: Object,
        required: true,
    },
});

// Formulario reactivo, inicializado con los datos cargados
const form = useForm({
    id: props.register.id,
    date: props.register.date || new Date().toISOString().substr(0, 10),
    customer_id: props.register.client_id || '', // Cliente inicial
    employe_id: props.register.employe_id || '',
    items_id: props.register.items_id || '',
    qty: props.register.qty || 0,
    searchQuery: props.register.items?.name || '',
    searchResults: []
});

// Incrementar cantidad
const increment = () => {
    form.qty++;
};

// Decrementar cantidad
const decrement = () => {
    if (form.qty > 0) form.qty--;
};

// Buscar piezas en tiempo real
const searchItems = async () => {
    if (form.searchQuery.length >= 2) {
        try {
            const response = await axios.post(route('items.searchName'), {
                params: { name: form.searchQuery }
            });
            form.searchResults = response.data.items;
        } catch (error) {
            console.error('Error buscando piezas:', error);
        }
    } else {
        form.searchResults = [];
    }
};

// Seleccionar pieza de la búsqueda
const selectItem = (item) => {
    form.items_id = item.id; // Guardar el ID del ítem
    form.searchQuery = item.name; // Reflejar el nombre en el input
    form.searchResults = []; // Limpiar resultados después de seleccionar
};

// Computed para verificar si el formulario tiene campos vacíos
const isFormInvalid = computed(() => {
    return !form.customer_id || !form.employe_id || !form.items_id || form.qty <= 0;
});

// Enviar formulario
const submitForm = () => {
    form.put(route('data.update', props.register.id), {
        onSuccess: () => {
            toast.warningToast("Se ha actualizado correctamente", { position: "top-right" });
        }
    });
};

// Inicializar formulario con los datos cargados
onMounted(() => {
    // Lógica adicional si es necesario cuando se monta el componente
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
