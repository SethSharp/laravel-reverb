<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

let count = ref(0)
const message = ref()
const messages = ref([])
const messageFromServer = ref(null)

const sendMessage = () => {
  let builtMsg = {
    id: Math.random(),
    message: message.value,
    who: 'Me'
  }

  messages.value.push(builtMsg)

  axios.post('/send-private-message', builtMsg)
      .then(res => {
        messageFromServer.value = res.data.message

        setTimeout(() => {
          messageFromServer.value = null
        }, 2500)
      })

  message.value = null
}

Echo.private('private-messages').listen('PrivateMessageReceived', (e) => {
  if (!messages.value.find(message => message.id === e.id)) {
    messages.value.push(e)
  }
})
</script>

<template>
  <AuthenticatedLayout class="dark:text-gray-400">
    <div class="p-5">
      Below is an open channel messaging system:

      <div class="flex w-full">
        <div class="w-1/2">
          <form @submit.prevent="sendMessage" class="flex space-y-4">
            <textarea v-model="message" />

            <button>
              Send message
            </button>
          </form>

          <span v-if="messageFromServer" class="text-green-500 text-sm">
          {{ messageFromServer }}
        </span>
        </div>

        <div class="w-1/2">
          Messages
          <ul>
            <li v-for="message in messages" :key="message.id">
              {{ message.who }}: {{ message.message }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>