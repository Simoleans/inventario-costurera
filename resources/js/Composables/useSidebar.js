// useSidebar.js
import { ref } from 'vue';

const isSidebarOpen = ref(false);

export const useSidebar = () => {
  const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
  };

  return { isSidebarOpen, toggleSidebar };
};
