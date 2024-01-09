<template>
  <div class="message-bubble" :class="{ 'mine': isMine, 'others': !isMine }">
    <div class="message-content">
      <p class="message-text">{{ data.message }}</p>
      <p class="message-time">{{ data.datetime }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: true
    },
    isMine: {
      type: Boolean,
      required: true
    }
  }
}
</script>

<style scoped>
.message-bubble {
  display: flex;
  margin-bottom: 10px;
}

.message-bubble.mine {
  justify-content: flex-end;
}

.message-bubble.others {
  justify-content: flex-start;
}

.message-content {
  max-width: 80%;
  padding: 10px;
  border-radius: 18px;
  color: white;
  word-wrap: break-word;
  overflow-wrap: break-word;
}

.message-bubble.mine .message-content {
  background-color: #0078ff; /* Blue color for the session user's messages */
}

.message-bubble.others .message-content {
  background-color: #e5e5ea; /* Grey color for other people's messages */
  color: black; /* Text color for other people's messages */
}

.message-text {
  white-space: normal;
}

.message-time {
  text-align: right;
  font-size: 0.75rem;
  margin-top: 8px;
  color: #999999;
}
</style>
