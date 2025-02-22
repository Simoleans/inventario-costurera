<template>
    <AppLayout title="Inventario">
      <!-- Aquí es donde agregas tu componente Section.vue -->
      <Section
        width="100%"
        marginBottom="20px"
        marginTop="10px"
      >
        <!-- Slots para el contenido del componente -->
        <template #title>
          <h1 class="text-2xl font-bold">Sistema</h1>
        </template>

        <template #content>
          <p>Costurero inventario.</p>
        </template>
      </Section>

    </AppLayout>
  </template>

  <script setup>
  // Importa el layout y el componente Section.vue
  import AppLayout from '@/Layouts/AppLayout.vue';
  import Section from '@/Components/Section.vue';
  </script>

  <style scoped>
  /* Estilos específicos de la página */
  </style>
