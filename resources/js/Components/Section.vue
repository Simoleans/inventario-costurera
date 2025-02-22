<template>
    <section
      :class="[baseClass, xlClass, xl3Class]"
      :style="{ width: width, marginBottom: marginBottom, marginTop: marginTop }"
    >
      <div class="bg-white py-6 px-4 rounded-[10px] shadow-md border border-slate-300 flex flex-col gap-6" :class="[baseClass, xlClass, xl3Class]">
        <slot name="title"></slot>
        <slot name="content"></slot>
      </div>
    </section>
  </template>

  <script setup>
   import {defineProps,ref} from 'vue';

  const props = defineProps({
    width: {
      type: String,
      default: '100%'
    },
    marginBottom: {
      type: String,
      default: '0'
    },
    marginTop: {
      type: String,
      default: '0'
    },
    xlClass: {
      type: String,
      default: '1xl:w-full'
    },
    xl3Class: {
      type: String,
      default: '3xl:w-full'
    }
  })

  const baseClass = 'w-full'
  </script>

  <style scoped>
  </style>
