<template>
  <form @submit.prevent="save">
    <h2 class="text-xl font-semibold mb-4" v-if="!task?.id">Dodaj zadanie</h2>
    <h2 class="text-xl font-semibold mb-4" v-else>Edytuj zadanie</h2>

    <div class="mb-4">
      <label class="block mb-1 font-medium">Nazwa zadania</label>
      <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" required />
    </div>

    <div class="mb-4">
      <label class="block mb-1 font-medium">Opis zadania</label>
      <textarea v-model="form.description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
    </div>

    <div class="flex justify-end gap-2">
      <button type="button" @click="$emit('cancel')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
        Anuluj
      </button>
      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Zapisz
      </button>
    </div>
  </form>
</template>

<script setup>
import { reactive, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  task: Object
})

const emit = defineEmits(['saved', 'cancel'])

const form = reactive({
  name: '',
  description: '',
})

watch(() => props.task, (newTask) => {
  if (newTask) {
    form.name = newTask.name
    form.description = newTask.description
  } else {
    form.name = ''
    form.description = ''
  }
}, { immediate: true })

const save = async () => {
  if (props.task?.id) {
    await axios.put(`/api/tasks/${props.task.id}`, form)
  } else {
    await axios.post('/api/tasks', form)
  }
  emit('saved')
}
</script>