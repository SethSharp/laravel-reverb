<script setup>
import { ref } from 'vue'

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

  axios.post('/send-message', builtMsg)
      .then(res => {
        messageFromServer.value = res.data.message

        setTimeout(() => {
          messageFromServer.value = null
        }, 2500)
      })

  message.value = null
}

Echo.channel('messages').listen('MessageReceived', (e) => {
  if (!messages.value.find(message => message.id === e.id)) {
    messages.value.push({
      ...e,
      who: 'Some random person'
    })
  }
})
</script>

<template>
  <div>
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
</template>