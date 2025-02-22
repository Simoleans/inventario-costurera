<template>
    <div>
      <!-- Ítem principal sin submenú -->
      <Link
        v-if="!item.submenu || !item.role"
        :href="route(item.routeName)"
        class="flex items-center justify-between w-full py-2 px-4 cursor-pointer"
        :class="{
          'bg-green-200 font-semibold': isActiveRoute(item.routeName),
          'hover:bg-green-200': !isActiveRoute(item.routeName)
        }"
        @click="openSidebar"
      >
        <div class="flex items-center space-x-4 w-full">
          <img :src="item.icon" alt="icon" class="w-6 h-6" />
          <span class="text-gray-700" v-if="isOpen">{{ item.name }}</span>
        </div>
      </Link>

      <!-- Ítem principal con submenú -->
      <div
        v-else
        class="flex items-center justify-between w-full py-2 px-4 cursor-pointer"
        @click="toggleSubmenu(item.name)"
        :class="{
          'bg-green-200 font-semibold': isActiveSubmenu(item.submenu),
          'hover:bg-green-200': !isActiveSubmenu(item.submenu)
        }"
      >
        <div class="flex items-center space-x-4 w-full">
          <img :src="item.icon" alt="icon" class="w-6 h-6" />
          <span class="text-gray-700" v-if="isOpen">{{ item.name }}</span>
        </div>
        <div class="ml-auto">
          <img
            :src="submenuOpen === item.name ? '/icons/up.svg' : '/icons/down.svg'"
            alt="arrow"
            class="w-4 h-4"
          />
        </div>
      </div>

      <!-- Submenú -->
      <div v-if="submenuOpen === item.name && isOpen" class="pl-6 space-y-2 mb-2">
        <Link
          v-for="(subitem, index) in item.submenu"
          :key="index"
          :href="route(subitem.routeName)"
          class="block py-2 rounded transition pl-2 mt-2 mb-2 border-l-4 border-gray-500"
          :class="{
            'bg-green-200 font-semibold border-l-4 border-green-500': isActiveRoute(subitem.routeName),
            'hover:bg-green-200': !isActiveRoute(subitem.routeName)
          }"
          @click="openSidebar"
        >
          {{ subitem.name }}
        </Link>
      </div>
    </div>
  </template>

  <script setup>
  import { defineProps, defineEmits } from 'vue';
  import { Link, usePage } from '@inertiajs/vue3'; // Link de Breeze y usePage para obtener la URL actual

  const props = defineProps({
    item: Object,
    submenuOpen: String,
    isOpen: Boolean,
  });

  const emit = defineEmits(['toggle-submenu', 'open-sidebar']); // Emitir evento para abrir el sidebar

  const page = usePage();

  // Función para alternar el submenú
  const toggleSubmenu = (name) => {
    emit('toggle-submenu', name);
    emit('open-sidebar'); // Emitir evento para abrir el sidebar si está cerrado
  };

  // Función para abrir el sidebar cuando se hace clic en un submenú
  const openSidebar = () => {
    emit('open-sidebar'); // Emitir evento para abrir el sidebar
  };

  // Verificar si la ruta actual coincide con la del ítem
  const isActiveRoute = (routeName) => {
    return route().current(routeName);
  };

  // Verificar si algún subítem del submenú está activo
  const isActiveSubmenu = (submenu) => {
    return submenu.some(subitem => route().current(subitem.routeName));
  };
  </script>

  <style scoped>
  .hover\:bg-green-200:hover {
    background-color: #e2e8f0; /* Hover gris claro */
  }

  .font-semibold {
    font-weight: 600; /* Aplicar font-semibold */
  }

  .border-l-4 {
    border-left-width: 4px; /* Borde izquierdo para subítem activo */
  }
  </style>
