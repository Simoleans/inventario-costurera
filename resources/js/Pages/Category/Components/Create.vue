<template>
    <transition>
      <div
        v-if="open"
        class="fixed bg-white shadow-xl flex-column"
        :class="{'w-full': isMobile, 'w-1/3': !isMobile}"
        :style="`top: ${containerTop}px; right: 0; min-height: calc(100vh - ${containerTop}px); height: calc(100vh - ${containerTop}px); z-index: 700;`"
        ref="ref_section_show"
      >
        <div class="overflow-y-auto scrolling-sticky" style="height: calc(100% - 72px)">
          <div class="flex justify-between items-center px-6 py-5">
            <div class="text-center">
              <h1 class="font-[500] text-[22px]">Crear Categoría</h1>
            </div>
            <div class="flex justify-end">
              <button @click="handleClose">X</button>
            </div>
          </div>
          <div class="border border-b"></div>
          <div class="py-3 px-6">
            <form class="space-y-4">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input
                  type="text"
                  required
                  id="name"
                  v-model="formData.name"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
              </div>
            </form>
          </div>
        </div>
        <!-- Botones Guardar y Cancelar -->
        <div
          class="tertiary-black-200 py-4 px-6 flex items-center justify-between border-t border-gray z-[1000] bg-white w-full"
          style="height: 72px;"
        >
          <button class="underline font-medium text-red-500" @click="handleClose">
            Cancelar
          </button>
          <button
            class="bg-green-500 hover:bg-green-600 text-white font-medium rounded-[6px] px-4 py-3 disabled:opacity-50"
            type="submit"
            :disabled="!isFormValid || formData.processing"
            @click="handleSave"
          >
            Guardar
          </button>
        </div>
      </div>
    </transition>
  </template>

  <script setup>
  import { ref, reactive, defineProps, defineEmits, computed, onMounted } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { useToastAlert } from '@/Composables/useToastAlert'

  const emit = defineEmits(['close', 'handleStore']);
  const props = defineProps({
    open: {
      type: Boolean,
      required: true,
    },
  });

  // Datos del formulario y estado inicial
  const formData = useForm({
    name: '',
  });

  let formInitialized = reactive({});
  const toast = useToastAlert();

  // Computed para verificar si el formulario es válido
  const isFormValid = computed(() => {
    return formData.name.trim() !== ''; // Verifica si ambos campos no están vacíos
  });

  // Función para inicializar el formulario
  const initializeForm = () => {
    formInitialized = { ...formData }; // Guardar el estado inicial del formulario
  };

  // Computed que detecta si hay cambios en el formulario
  const changes = computed(() => {
    return JSON.stringify(formData) !== JSON.stringify(formInitialized);
  });

  // Detectar si la pantalla es móvil
  const isMobile = ref(false);

  const updateMobileView = () => {
    isMobile.value = window.innerWidth <= 640; // Definir como móvil si el ancho de la pantalla es menor o igual a 640px
  };

  // Guardar el formulario y reiniciar el estado
  const handleSave = () => {
    formData.post(route('category.store'), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
        initializeForm();
        toast.warningToast("Se ha registrado correctamente", { position: "top-right" });
      },
    });
  };

  // Cerrar el formulario con advertencia si hay cambios
  const handleClose = () => {
    if (changes.value) {
      if (confirm('Tienes cambios sin guardar. ¿Estás seguro de que deseas cerrar?')) {
        closeModal();
      }
    } else {
      closeModal();
    }
  };

  // Función para cerrar el formulario
  const closeModal = () => {
    emit('close');
    formData.name = '';
  };

  // Controlar la altura del contenedor
  const containerTop = ref(0);
  const ref_section_show = ref(null);

  onMounted(() => {
    const sectionExpElement = document.getElementById('layout-c');
    if (sectionExpElement) {
      containerTop.value = sectionExpElement.offsetTop;
    }
    initializeForm(); // Inicializar el formulario cuando se monta el componente

    updateMobileView(); // Comprobar el estado móvil al montar
    window.addEventListener('resize', updateMobileView); // Actualizar vista al cambiar el tamaño de la pantalla
  });
  </script>

  <style scoped>
  /* Estilos de transición */
  .v-enter-active, .v-leave-active {
    transition: transform 0.5s ease;
  }

  .v-enter-from, .v-leave-to {
    transform: translateX(100%);
  }

  .scrolling-sticky {
    &::-webkit-scrollbar {
      -webkit-appearance: none;
    }

    &::-webkit-scrollbar:vertical {
      width: 16px;
    }

    &::-webkit-scrollbar-thumb {
      background-color: #BFBFBF;
      border-radius: 20px;
      border: 4px solid #fff;
    }

    &::-webkit-scrollbar-track {
      border-radius: 10px;
      background-color: #fff;
    }
  }

  .modal-fade-enter-active, .modal-fade-leave-active {
    transition: opacity 0.4s ease;
  }

  .modal-fade-enter-from, .modal-fade-leave-to {
    opacity: 0;
  }

  .modal-fade-enter-to, .modal-fade-leave-from {
    opacity: 1;
  }
  </style>
