<template>
    <div class="relative">
      <!-- Overlay oscuro cuando el sidebar está abierto en responsive -->
      <div
        v-if="isSidebarOpen && isClient && isSmallScreen"
        class="fixed inset-0 bg-black opacity-50 z-40"
        @click="closeSidebar"
      ></div>

      <!-- Sidebar -->
      <div
        :class="[
          'sidebar',
          isOpen ? 'open' : 'closed',
          isSidebarOpen ? 'block' : 'hidden',
          'md:relative',
          'md:block',
          'absolute top-0 left-0 h-screen z-50 md:z-auto'
        ]"
        class="bg-white text-gray-800 transition-all duration-300 ease-out border-r border-black flex flex-col justify-between"
      >
        <div>
            <!-- Botón de abrir/cerrar sidebar en su posición original -->
            <div class=" pt-2 items-center justify-center hidden md:lg:flex">
                <button @click="toggleSidebarContent" class="md:flex items-center px-3 py-2 border rounded text-gray-800 border-gray-400 hover:text-black hover:border-black">
                    <svg v-if="isOpen"  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5M10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5"/>
                    </svg>
                    <svg  v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8m-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5"/>
                    </svg>
                </button>
            </div>
          <!-- Espacio para el logo o texto "702" -->
          <div class="p-4 flex items-center justify-center">
            <template v-if="isOpen || isSmallScreen">
              <!-- Mostrar el logo completo cuando el sidebar está abierto o en modo responsive -->
              <img src="/icons/logo.png" alt="Logo" class="w-full h-auto" />
            </template>
            <template v-else>
              <!-- Mostrar "702" cuando el sidebar está cerrado en pantallas grandes -->
              <span class="text-2xl font-bold">702</span>
            </template>
          </div>


          <!-- Menú -->
          <nav class="flex flex-col p-0">
            <SidebarItem
              v-for="(item, index) in visibleMenuItems"
              :key="index"
              :item="item"
              :submenu-open="submenuOpen"
              @toggle-submenu="toggleSubmenu"
              @open-sidebar="openSidebar"
              :is-open="isOpen"
            />
          </nav>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, watch, onMounted, onUnmounted, computed } from 'vue';
  import SidebarItem from './SidebarItem.vue';
  import { Link, usePage } from '@inertiajs/vue3';
  import { useSidebar } from '@/Composables/useSidebar'; // Importamos el composable

  // Estado y funcionalidad para manejar el sidebar
  const { isSidebarOpen, toggleSidebar } = useSidebar();
  const isOpen = ref(true); // Estado para manejar si el sidebar está abierto o cerrado
  const submenuOpen = ref(null);
  const isClient = ref(false); // Verificar si estamos en el cliente
  const isSmallScreen = ref(false); // Verificar si es una pantalla pequeña

  // Obtener la información del usuario autenticado y sus roles
  const $page = usePage();
  const isAdmin = $page.props.auth.role.name === 'admin';
  const isRegistrador = $page.props.auth.role.name === 'registrador';

  // Datos de los items del menú con submenús y roles
  const menuItems = ref([
    {
      name: 'Inicio',
      icon: '/icons/home.png',
      routeName: 'dashboard',
    },
    {
      name: 'Usuarios',
      icon: '/icons/data.png',
      submenu: [
        { name: 'Ver Usuarios', routeName: 'users.index' },
        { name: 'Crear Usuario', routeName: 'users.create' },
      ],
      role:isAdmin,
    },
    /*{
      name: 'Bitácora',
      icon: '/icons/data.png',
      submenu: [
        { name: 'Ver Bitácora', routeName: 'data.index' },
        { name: 'Crear Bitácora', routeName: 'data.create' },
      ],
      role: isRegistrador || isAdmin,
    },
    {
      name: 'Configuración',
      icon: '/icons/configuraciones.png',
      submenu: [
        { name: 'Costureros', routeName: 'employe.index' },
        { name: 'Items', routeName: 'items.index' },
        { name: 'Clientes', routeName: 'customer.index' },
      ],
      role: isAdmin,
    },*/
  ]);

  // Filtrar los items visibles según el rol del usuario
  const visibleMenuItems = computed(() => {
    return menuItems.value.filter(item => {
      if (item.role === undefined) return true; // Sin restricción de rol
      return item.role; // Sólo si el rol es verdadero
    });
  });

  // Función para alternar el sidebar con el botón
  const toggleSidebarContent = () => {
    isOpen.value = !isOpen.value;
  };

  // Función para alternar el submenú
  const toggleSubmenu = (name) => {
    submenuOpen.value = submenuOpen.value === name ? null : name; // Alternar el submenú
  };

  // Función para abrir el sidebar
  const openSidebar = () => {
    if (!isOpen.value) {
      isOpen.value = true; // Abrir el sidebar si está cerrado
    }
  };

  // Verificar el tamaño de la pantalla
  const checkScreenSize = () => {
    isSmallScreen.value = isClient.value && window.innerWidth < 768;
  };

  // Detectar si estamos en cliente y verificar el tamaño de pantalla
  onMounted(() => {
    isClient.value = typeof window !== 'undefined';
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize); // Detectar cambio de tamaño
  });

  // Limpiar el event listener al desmontar
  onUnmounted(() => {
    window.removeEventListener('resize', checkScreenSize);
  });

  // Sincronizar el estado de `isOpen` cuando se abra el sidebar en responsive
  watch(isSidebarOpen, (newValue) => {
    if (isSmallScreen.value) {
      isOpen.value = newValue; // Abrir completamente en responsive si el sidebar está abierto
    }
  });

  // Cerrar el sidebar cuando se haga clic en el overlay oscuro
  const closeSidebar = () => {
    toggleSidebar(); // Cambiar el estado del sidebar
  };
  </script>

  <style scoped>
  .sidebar {

    transition: width 0.3s ease-out; /* Transición más rápida */
  }

  .sidebar.open {
    width: 260px; /* Ancho del sidebar cuando está abierto */
  }

  /* Para pantallas pequeñas, el sidebar siempre ocupará todo el ancho cuando esté abierto */
  @media (max-width: 768px) {
    .sidebar {
      width: 260px;
      transition: width 0.3s ease-out; /* Transición más rápida en responsive */
    }
  }
  </style>
