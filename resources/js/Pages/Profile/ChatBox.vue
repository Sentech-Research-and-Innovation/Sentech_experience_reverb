<template>
    <div class="chat-box">
      <div class="chat-header">
        <strong>{{ receiver.first_name }} {{ receiver.last_name }}</strong>
      </div>
  
      <div class="chat-messages" id="messages">
        <div
          v-for="(msg, index) in messages"
          :key="index"
          :class="['chat-message', msg.sender_id === userId ? 'sent' : 'received']"
        >
          {{ msg.message }}
        </div>
      </div>
  
      <div class="chat-input">
        <input
          v-model="newMessage"
          @keyup.enter="sendMessage"
          placeholder="Type a message..."
        />
        <button @click="sendMessage">Send</button>
      </div>
    </div>
  </template>
  
  <script>
  import Echo from "@/bootstrap/echo"; // your echo.js config
  
  export default {
    props: { receiver: Object },
    data() {
      return {
        userId: window.Laravel?.user?.id || null, // make sure user ID is available globally
        messages: [],
        newMessage: "",
      };
    },
    mounted() {

    if (!this.userId) {
      console.error("User ID is not defined!");
      return; // prevent further execution if no userId
        } 

      const senderId = this.userId;
      const receiverId = this.receiver.id;
      const channelName = "chat." + [senderId, receiverId].sort().join("-");
  
      // Listen for real-time messages
      Echo.channel(channelName).listen("MessageSent", (event) => {
        this.messages.push(event.message);
      });
  
      // Load existing messages (from your Laravel controller route)
      axios.get(`/chat/${receiverId}`).then((res) => {
        this.messages = res.data;
      });
    },
    methods: {
      sendMessage() {
        if (!this.newMessage.trim()) return;
  
        axios
          .post("/chat/send", {
            receiver_id: this.receiver.id,
            message: this.newMessage,
          })
          .then(() => {
            this.newMessage = "";
          });
      },
    },
  };
  </script>
  
  <style scoped>
  .chat-box {
    width: 340px;
    border: 1px solid #ccc;
    border-radius: 10px;
    margin: 30px auto;
    display: flex;
    flex-direction: column;
    background: #fff;
  }
  .chat-header {
    background: #1877f2;
    color: white;
    padding: 10px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .chat-messages {
    max-height: 300px;
    overflow-y: auto;
    padding: 10px;
  }
  .chat-message {
    padding: 8px 12px;
    border-radius: 15px;
    margin-bottom: 8px;
    max-width: 75%;
  }
  .sent {
    background: #1877f2;
    color: white;
    align-self: flex-end;
  }
  .received {
    background: #f1f0f0;
    color: black;
    align-self: flex-start;
  }
  .chat-input {
    display: flex;
    border-top: 1px solid #ccc;
  }
  .chat-input input {
    flex: 1;
    border: none;
    padding: 10px;
  }
  .chat-input button {
    background: #1877f2;
    color: white;
    border: none;
    padding: 0 15px;
    cursor: pointer;
  }
  </style>
