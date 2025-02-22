<template>
    <div class="relative flex h-screen">
      <!-- Pantalla de carga -->
      <LoadingScreen v-if="isLoading" />

      <!-- Contenido principal -->
      <div v-if="!isLoading" class="flex h-screen w-full">
        <!-- Sidebar -->
        <transition name="slide">
          <Sidebar
            :hover-active="hoverActive"
            v-show="sidebarVisible"
            class="fixed inset-y-0 left-0  bg-white md:relative md:inset-auto md:w-auto z-50"
          />
        </transition>

        <!-- Contenido principal -->
        <div class="flex-1 flex flex-col w-full md:w-auto">
          <!-- Navbar -->
          <Navbar :title="props.title" />

          <!-- Contenido -->
          <main class="flex-1 overflow-y-auto p-4 bg-gray-100">
            <slot />
          </main>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import Sidebar from '@/Layouts/Components/Sidebar.vue';
  import Navbar from '@/Layouts/Components/Navbar.vue';
  import LoadingScreen from '@/Components/LoadingScreen.vue'; // Importar el componente de carga
  import { ref, onMounted } from 'vue';

  // Puedes pasar un prop para controlar si el sidebar siempre está abierto
  const hoverActive = ref(false);  // Cambia esto según necesites

  // Prop del título para el Navbar
  const props = defineProps({
    title: {
      type: String,
      required: true,
      default: 'Laravel',
    },
  });

  // Controlar la carga de la página
  const isLoading = ref(true);

  // Controlar la visibilidad del sidebar
  const sidebarVisible = ref(true);  // Cambia esto a false para que el sidebar esté inicialmente oculto

  // Alternar la visibilidad del sidebar
  const toggleSidebar = () => {
    sidebarVisible.value = !sidebarVisible.value;
  };

  onMounted(() => {
    // Simulamos una carga de contenido durante 2 segundos
    setTimeout(() => {
      isLoading.value = false; // Ocultamos la pantalla de carga
    }, 1100); // Ajusta este tiempo o conéctalo con el proceso real de carga
  });
  </script>

  <style scoped>
  /* Transición del Sidebar */
  .slide-enter-active, .slide-leave-active {
    transition: transform 0.3s ease-in-out;
  }
  .slide-enter-from, .slide-leave-to {
    transform: translateX(-100%);
  }

  @media (max-width: 768px) {
    /* En pantallas móviles, Sidebar cubre toda la pantalla con un z-index alto */
    .sidebar-mobile {
      position: fixed;
      top: 0;
      left: 0;
      width: 16rem; /* Ajusta este valor según el ancho deseado */
      height: 100%;
      z-index: 50;
      background-color: white;
    }
  }
  </style>
