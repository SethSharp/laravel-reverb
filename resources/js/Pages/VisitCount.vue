<script setup>
import { ref } from 'vue'
let count = ref(0)

Echo.channel('hello-world').listen('HelloWorld', (e) => {
  count.value = count.value + 1
})
</script>

<template>
  <div>
    The visit count is {{ count }}
  </div>
</template>