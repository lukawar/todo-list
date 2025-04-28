import axios from 'axios'

export function fetchTasks() {
  return axios.get('/api/tasks');
}

export function createTask(task) {
  return axios.post('/api/tasks', task);
}