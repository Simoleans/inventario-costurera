<template>
<Head :title="props.title" />
    <div class="border-b border-black ">
      <div class=" w-full px-4 py-4 flex justify-between items-center">
        <div class="text-xl font-semibold">
          <span>{{ props.title }}</span>
        </div>

        <!-- Botón de menú (aparece en pantallas pequeñas) -->
        <button @click="toggleSidebar" class="md:hidden flex items-center px-3 py-2 border rounded text-gray-800 border-gray-400 hover:text-black hover:border-black">
          <svg class="fill-current h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M0 3h20v2H0V3zm0 5h20v2H0V8zm0 5h20v2H0v-2z" />
          </svg>
        </button>

        <!-- Botón Cerrar Sesión (visible en todas las pantallas) -->
        <div class="hidden md:flex items-center">
          <button @click="confirmLogout" class="flex items-center bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left mr-2" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
              <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
            </svg>
            <span>Cerrar sesión</span>
          </button>
        </div>
      </div>

      <!-- Botón Cerrar Sesión en responsive -->
      <div class="flex md:hidden justify-end px-4 py-2">
        <button @click="confirmLogout" class="flex items-center bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left mr-2" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
            <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
          </svg>
          <span>Cerrar sesión</span>
        </button>
      </div>
    </div>
  </template>

  <script setup>
  import { defineProps } from 'vue';
  import { useSidebar } from '@/Composables/useSidebar'; // Importamos el composable
  import { router,Head } from '@inertiajs/vue3'; // Para manejar la navegación con Inertia.js


  const props = defineProps({
    title: {
      type: String,
      required: true,
      default: 'Laravel',
    },
  });

  const { toggleSidebar } = useSidebar(); // Accedemos a la función para alternar el sidebar

  // Función para confirmar la salida antes de cerrar sesión
  const confirmLogout = () => {
    if (confirm('¿Estás seguro que deseas cerrar sesión?')) {
      // Usamos la navegación de Inertia.js para hacer logout
      router.post(route('logout'));
    }
  };
  </script>

  <style scoped>
  /* Estilos opcionales */
  </style>
