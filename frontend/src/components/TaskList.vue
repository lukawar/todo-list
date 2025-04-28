<template>
  <div class="flex-1 flex flex-col w-full p-6">

    <ConfirmationModal
      :show="confirmModal.show"
      :title="confirmModal.title"
      :message="confirmModal.message"
      @confirm="handleConfirm"
      @cancel="confirmModal.show = false"
    />

    <div v-if="saving" class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
      <div class="bg-white p-4 rounded shadow flex items-center gap-2">
        <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
        </svg>
        <span>Cierpliwości...</span>
      </div>
    </div>

    
    <div class="flex justify-end mb-4 spacer">
      <button @click="openCreateModal" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Dodaj zadanie
      </button>
    </div>

    <div v-if="isLoading" class="flex justify-center items-center flex-1">
      <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
      </svg>
    </div>

    <div v-else class="overflow-x-auto">
      <table class="w-full min-w-full bg-white shadow-md rounded-md">
        <thead>
          <tr class="bg-gray-200 text-gray-700">
            <th class="text-left px-6 py-3">Zadanie</th>
            <th class="text-left px-6 py-3">Status</th>
            <th class="text-left px-6 py-3">Data dodania</th>
            <th class="text-left px-6 py-3"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in tasks" :key="task.id" class="border-t hover:bg-gray-50">
            <td class="px-6 py-4">{{ task.name }}</td>
            <td class="px-6 py-4 capitalize">{{ task.status }}</td>
            <td class="px-6 py-4">{{ task.created_at }}</td>
            <td class="px-6 py-4">
              <div class="flex justify-end gap-2">
                <button @click="openEditModal(task)" class="flex items-center gap-1 text-blue-600 hover:underline" v-if="task.status !== 'completed'">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l4 4L20.5 7.5a2.121 2.121 0 00-3-3L9 11zm0 0v4h4" />
                  </svg>
                  Edytuj
                </button>
                <button @click="completeTask(task.id)" class="flex items-center gap-1 text-green-600 hover:underline" v-if="task.status !== 'completed'">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Zakończ
                </button>
                <button @click="confirmDelete(task.id)" class="flex items-center gap-1 text-red-600 hover:underline">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  Usuń
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="pagination flex justify-center mt-6 gap-2 spacer">
        <button
          v-for="link in pagination.links"
          :key="link.label"
          :disabled="!link.url"
          @click="changePage(link.url)"
          class="px-4 py-2 rounded border"
          :class="{
            'bg-blue-600 text-white': link.active,
            'bg-white text-gray-600 hover:bg-gray-100': !link.active
          }"
          v-html="link.label"
        />
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded shadow max-w-lg w-full">
        <TaskForm :task="selectedTask" @saved="handleSaved" @cancel="showModal = false" />
      </div>
    </div>

    <!-- Snackbar -->
    <div v-if="snackbar.show" class="fixed bottom-6 right-6 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
      {{ snackbar.message }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import TaskForm from './TaskForm.vue'
import ConfirmationModal from './ConfirmationModal.vue'

const tasks = ref([])
const pagination = ref({ links: [] })
const isLoading = ref(true)

const showModal = ref(false)
const selectedTask = ref(null)
const snackbar = ref({ show: false, message: '' })
const confirmModal = ref({ show: false, action: null, title: '', message: '', payload: null })
const saving = ref(false)

const loadTasks = async (url = '/api/tasks') => {
  isLoading.value = true
  const res = await axios.get(url)
  tasks.value = res.data.data
  pagination.value = res.data.meta
  isLoading.value = false
}

const changePage = async (url) => {
  if (!url) return
  await loadTasks(url.replace('http://localhost', ''))
}

const openCreateModal = () => {
  selectedTask.value = null
  showModal.value = true
}

const openEditModal = (task) => {
  selectedTask.value = task
  showModal.value = true
}

const handleSaved = (updatedTask) => {
  showModal.value = false
  if (updatedTask?.id) {
  
    const index = tasks.value.findIndex(t => t.id === updatedTask.id)
    if (index !== -1) {
      tasks.value.splice(index, 1, updatedTask)
    }
  } else {
      loadTasks()
  }
  showSnackbar('Zadanie zapisane!')
}

const confirmDelete = (id) => {
  confirmModal.value = {
    show: true,
    title: 'Usuń zadanie',
    message: 'Czy na pewno chcesz usunąć to zadanie?',
    action: () => deleteTask(id),
    payload: id
  }
}

const deleteTask = async (id) => {
  saving.value = true
  await axios.delete(`/api/tasks/${id}`)
  tasks.value = tasks.value.filter(t => t.id !== id)
  showSnackbar('Zadanie usunięte!')
  saving.value = false
}

const completeTask = (id) => {
  confirmModal.value = {
    show: true,
    title: 'Zakończ zadanie',
    message: 'Czy na pewno chcesz zakończyć to zadanie?',
    action: () => finalizeTask(id),
    payload: id
  }
}

const finalizeTask = async (id) => {
  saving.value = true
  const res = await axios.patch(`/api/tasks/${id}`, { status: 'completed' })
  const updatedTask = res.data.data

  const index = tasks.value.findIndex(t => t.id === id)
  if (index !== -1) {
    tasks.value[index] = updatedTask
  }
  showSnackbar('Zadanie zakończone!')
  saving.value = false
}

const showSnackbar = (message) => {
  snackbar.value = { show: true, message }
  setTimeout(() => {
    snackbar.value.show = false
  }, 3000)
}

const handleConfirm = () => {
  if (confirmModal.value.action) {
    confirmModal.value.action(confirmModal.value.payload)
  }
  confirmModal.value.show = false
}

onMounted(loadTasks)
</script>

<style scoped>
  .spacer {
    margin: 20px 0;
  }

</style>
