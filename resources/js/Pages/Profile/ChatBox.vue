<template>
  <div class="chat-box">
    <!-- Header -->
    <div class="chat-header">
      <img
        :src="receiver.profile_picture || '/images/default-avatar.png'"
        alt="User avatar"
        class="chat-avatar"
      />
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
      <div v-for="(group, date) in groupedMessages" :key="date" class="date-group">
        <div class="date-label">{{ formatDateLabel(date) }}</div>

        <div
          v-for="(msg, index) in group"
          :key="index"
          :class="['chat-message', msg.sender_id === userId ? 'sent' : 'received']"
        >
          <div class="bubble">
            <p>{{ msg.message }}</p>
            <small class="timestamp">{{ formatTime(msg.created_at) }}</small>
          </div>
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
      typing: false,
    };
  },
  computed: {
    groupedMessages() {
      const groups = {};
      this.messages.forEach((msg) => {
        const date = msg.created_at?.split(" ")[0];
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

    Echo.channel(channelName).listen("MessageSent", (event) => {
      this.messages.push(event.message);
      this.scrollToBottom();
    });

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
          this.typing = false;
        });
    },
    scrollToBottom() {
      this.$nextTick(() => {
        const container = document.getElementById("messages");
        if (container) container.scrollTop = container.scrollHeight;
      });
    },
    formatTime(datetime) {
      const date = new Date(datetime);
      return date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
    },
    formatDateLabel(dateStr) {
      const date = new Date(dateStr);
      const today = new Date();
      const diffDays = Math.floor((today - date) / (1000 * 60 * 60 * 24));

      if (diffDays === 0) return "Today";
      if (diffDays === 1) return "Yesterday";
      return date.toLocaleDateString([], { day: "2-digit", month: "short", year: "numeric" });
    },
    formatLastSeen(datetime) {
      const date = new Date(datetime);
      return date.toLocaleString([], {
        weekday: "short",
        hour: "2-digit",
        minute: "2-digit",
      });
    },
  },
};
</script>

<style scoped>
.chat-box {
  width: 380px;
  border: 1px solid #ddd;
  border-radius: 10px;
  margin: 20px auto;
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

.chat-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
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
  flex: 1;
  overflow-y: auto;
  padding: 10px;
  background: #ece5dd;
}

.date-group {
  text-align: center;
  margin-bottom: 10px;
}

.date-label {
  background: #d4d4d4;
  color: #333;
  display: inline-block;
  padding: 4px 10px;
  border-radius: 10px;
  font-size: 12px;
  margin-bottom: 8px;
}

.chat-message {
  display: flex;
  margin-bottom: 6px;
}

.bubble {
  position: relative;
  padding: 8px 12px;
  border-radius: 10px;
  max-width: 75%;
  font-size: 14px;
  line-height: 1.4;
}

.sent .bubble {
  background: #dcf8c6;
  margin-left: auto;
}

.received .bubble {
  background: #fff;
  margin-right: auto;
}

.timestamp {
  display: block;
  font-size: 10px;
  color: gray;
  text-align: right;
  margin-top: 3px;
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
