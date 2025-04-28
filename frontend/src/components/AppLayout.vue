<template>
    <div class="min-h-screen bg-gray-100 flex flex-col">
      <header class="bg-blue-500 text-white py-4 shadow-md">
        <div class="container mx-auto px-4">
          <h1 class="text-xl font-semibold">TODO LIST</h1>
        </div>
      </header>
  
      <main class="flex-grow container mx-auto px-4 py-8">
        <TaskList />
      </main>
  
      <footer class="bg-gray-200 text-gray-600 py-3 text-center border-t">
        <div class="container mx-auto px-4">
          <p>&copy; 2025 lwargula@gmail.com</p>
        </div>
      </footer>
    </div>
  </template>
  
  <script setup>
    import TaskList from './TaskList.vue';
  </script>