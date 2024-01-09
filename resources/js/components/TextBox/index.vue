<template>
    <div v-if="state.isChatBoxOpen" class="chat-container">
        <div class="chat-header">
            <h2>Chatbox</h2>
            <button class="close-btn" @click="toggleChatBox">X</button>
        </div>
        <div class="chat-messages" id="chat-messages">
            <message-bubble
                v-for="message in state.messages" 
                :key="message.id"
                :data="message"
                :isMine="isMessageMine(message.user_type)"
            />
        </div>
        <div class="chat-input">
            <input type="text" id="message-input" v-model="message" placeholder="Type a message...">
            <button id="send-button" @click="submitMessage">Send</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import MessageBubble from '../MessageBubble/index.vue'; // Import the MessageBubble component
import { watch, reactive, onMounted, onBeforeUnmount, ref } from 'vue';


export default {
    components: {
        MessageBubble // Register the MessageBubble component
    },
    setup() {
        // const isVisible = ref(false);

        // Reactive state
        const state = reactive({
            isChatBoxOpen: false,
            messages: [],
            ic: null,
            sessionUserId: window.Laravel.sessionUserId,
            status: null
        });
        

        const message = ref(''); // Use ref for single primitive value

        // Method to handle the custom event
        const getMessage = (event) => {
            state.ic = event.detail.ic; // Set IC from event details
            // Perform operations based on the new IC value
            // For example, fetching messages
            // axios.get(`/api/messages/${state.ic}`).then(response => {
            //     state.messages = response.data;
            // });

            state.isChatBoxOpen = true;
        };

        // Function to toggle chat box visibility
        const toggleChatBox = () => {
            state.isChatBoxOpen = !state.isChatBoxOpen;
        };

        // Function to determine if the message is from the current user
        const isMessageMine = (messageSender) => {
            return messageSender === state.sessionUserId;
        };

        // Function to fetch messages
        const fetchMessages = () => {
            axios.post('/all/massage/user/getMassage', { 
                ic: state.ic,
                type: state.sessionUserId
                })
                .then(response => {
                    state.messages = response.data;
                })
                .catch(error => {
                    console.error('Error fetching messages:', error);
                });
        };

        let pollingInterval = null;

        const startPolling = () => {
            // Start polling only if the chat box is open
            if (state.isChatBoxOpen && !pollingInterval) {
                pollingInterval = setInterval(fetchMessages, 500);
            }
        };

        const stopPolling = () => {
            // Clear the polling interval if it exists
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null; // Reset the interval variable
            }
        };

        // Watch for changes in state.isChatBoxOpen
        watch(() => state.isChatBoxOpen, (newValue) => {
            if (newValue) {
                startPolling();
            } else {
                stopPolling();
            }
        }, { immediate: true });


        // Define your methods here
        const submitMessage = () => {
            if (message.value.trim() === '') {
                alert("Please enter a message.");
                return;
            }
            // Axios call
            axios.post('/all/massage/user/sendMassage', {
                    message: message.value, // The message content
                    ic: state.ic,            // The IC value from your state
                    type: state.sessionUserId
                })
                .then(response => {
                    console.log(response.data.message);
                    message.value = ''; // Clear the message input
                })
                .catch(error => {
                    console.error("Error sending message:", error);
                });
        };

        // Lifecycle hooks for event listener
        onMounted(() => {
            window.addEventListener('message-requested', getMessage);
            fetchMessages();
            startPolling();
        });

        onBeforeUnmount(() => {
            window.removeEventListener('message-requested', getMessage);
            clearInterval(pollingInterval);
        });

        console.log(state);

        // Return everything that needs to be accessible in the template
        return { state, message, toggleChatBox, isMessageMine, submitMessage };

    }
}
</script>

<style scoped>
.chat-container {
    position: fixed;
    bottom: 40px; 
    right: 0;
    width: 300px;
    border: 1px solid #ddd;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    z-index: 1000;
}

.chat-header {
    background-color: #007bff;
    color: #fff;
    padding: 10px;
    text-align: center;
    position: relative;
}

.close-btn {
    position: absolute;
    top: 0;
    right: 0;
    border: none;
    background: none;
    color: #fff;
    cursor: pointer;
}

.chat-messages {
    height: 300px;
    overflow-y: auto;
    padding: 10px;
    border-top: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
}

.chat-input {
    padding: 10px;
    display: flex;
}

.chat-input input[type="text"] {
    flex: 1;
    padding: 10px;
    margin-right: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.chat-input button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.chat-input button:hover {
    background-color: #0056b3;
}

</style>
