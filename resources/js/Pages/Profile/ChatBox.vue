<template>
    <div class="chat-box">
        <!-- Header -->
        <div class="chat-header">
            <div class="chat-user-info">
                <strong>{{ receiver.first_name }} {{ receiver.last_name }}</strong>
                <small v-if="receiver.last_seen">
                Last seen {{ formatLastSeen(receiver.last_seen) }}
                </small>
                <small v-else>Online</small>
            </div>

        </div>

        <!-- Messages -->
      <div class="chat-messages" id="messages">
        <div
          v-for="(group, date) in groupedMessages"
          :key="date"
          class="date-group"
        >
          <div class="date-header">{{ formatDateHeader(date) }}</div>
  
          <div
            v-for="(msg, index) in group"
            :key="index"
            :class="[
              'chat-message',
              msg.sender_id === userId ? 'sent' : 'received',
            ]"
          >
            {{ msg.message }}
          </div>
        </div>
      </div>
  
      <!-- Input -->
        <div class="chat-input">
        <input
            v-model="newMessage"
            placeholder="Type a message..."
            @input="typing = newMessage.trim().length > 0"
            @keyup.enter="sendMessage"
        />
        <button v-if="typing" @click="sendMessage" class="send-btn">
            <i class="fas fa-paper-plane"></i>
        </button>
        </div>
    </div>
  </template>
  
  <script>
  import Echo from "@/bootstrap/echo";
  import axios from "axios";
  
  export default {
    props: { receiver: Object },
    data() {
      return {
        userId: window.Laravel?.user?.id || null,
        messages: [],
        newMessage: "",
      };
    },
    computed: {
      groupedMessages() {
        const groups = {};
        this.messages.forEach((msg) => {
          const date = msg.created_at.split("T")[0]; // assumes ISO timestamp
          if (!groups[date]) groups[date] = [];
          groups[date].push(msg);
        });
        return groups;
      },
    },
    mounted() {
      if (!this.userId) {
        console.error("User ID is not defined!");
        return;
      }
  
      const senderId = this.userId;
      const receiverId = this.receiver.id;
      const channelName = "chat." + [senderId, receiverId].sort().join("-");
  
      // Listen for new messages
      Echo.channel(channelName).listen("MessageSent", (event) => {
        this.messages.push(event.message);
        this.scrollToBottom();
      });
  
      // Load previous chat
      axios.get(`/profile/chat/${receiverId}`).then((res) => {
        this.messages = res.data;
        this.scrollToBottom();
      });
    },
    methods: {
      sendMessage() {
        if (!this.newMessage.trim()) return;
  
        axios
          .post("/profile/chat/send", {
            receiver_id: this.receiver.id,
            message: this.newMessage,
          })
          .then(() => {
            this.newMessage = "";
          });
      },
      formatDateHeader(date) {
        const today = new Date();
        const messageDate = new Date(date);
        const yesterday = new Date();
        yesterday.setDate(today.getDate() - 1);
  
        if (messageDate.toDateString() === today.toDateString()) return "Today";
        if (messageDate.toDateString() === yesterday.toDateString())
          return "Yesterday";
  
        return messageDate.toLocaleDateString(undefined, {
          weekday: "short",
          month: "short",
          day: "numeric",
        });
      },
      scrollToBottom() {
        this.$nextTick(() => {
          const container = document.getElementById("messages");
          if (container) container.scrollTop = container.scrollHeight;
        });
      },
    },
  };
  </script>
  
  <style scoped>
  .chat-box {
    width: 340px;
    border: 1px solid #ddd;
    border-radius: 10px;
    margin: 30px auto;
    display: flex;
    flex-direction: column;
    background: #ece5dd;
    font-family: Arial, sans-serif;
  }

  .chat-header {
  display: flex;
  align-items: center;
  background: #075e54;
  color: white;
  padding: 10px;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.chat-user-info strong {
  display: block;
  font-size: 14px;
}

.chat-user-info small {
  font-size: 12px;
  opacity: 0.8;
}


  .chat-messages {
    max-height: 400px;
    overflow-y: auto;
    padding: 10px;
    display: flex;
    flex-direction: column;
  }
  .date-group {
    margin-bottom: 15px;
  }
  .date-header {
    text-align: center;
    color: #777;
    font-size: 12px;
    margin: 10px 0;
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
    align-self: flex-start; /* sender's message on left */
  }
  .received {
    background: #f1f0f0;
    color: black;
    align-self: flex-end;
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
  .chat-input {
  display: flex;
  align-items: center;
  padding: 8px;
  background: #fff;
  border-top: 1px solid #ddd;
}

.chat-input input {
  flex: 1;
  border: none;
  outline: none;
  background: #f0f0f0;
  border-radius: 20px;
  padding: 10px 15px;
  font-size: 14px;
}


.send-btn {
  background: #075e54;
  border: none;
  color: white;
  font-size: 18px;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  margin-left: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.send-btn:hover {
  background: #128c7e;
}
  </style>
  
