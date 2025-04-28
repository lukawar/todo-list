<template>
    <div v-if="show" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded shadow max-w-md w-full text-center">
        <h2 class="text-lg font-semibold mb-4">{{ title }}</h2>
        <p class="mb-6">{{ message }}</p>
        <div class="flex justify-center gap-4">
          <button @click="$emit('cancel')" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Anuluj</button>
          <button @click="$emit('confirm')" class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white">Potwierdź</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    show: Boolean,
    title: String,
    message: String
  })
  defineEmits(['confirm', 'cancel'])
  </script>