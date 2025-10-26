<template>
    <div class="chat-box">
      <!-- Header -->
      <div class="chat-header" @click="toggleMinimize" role="button" aria-expanded="showChat">
        <div class="header-left">
          <div v-if="receiver.profile_photo_url" class="profile-wrapper">
            <img
              :src="receiver.profile_photo_url"
              alt="Profile"
              class="profile-img"
              @error="onImageError"
            />
          </div>
          <div
            v-else
            class="profile-initial"
            :style="{ backgroundColor: avatarColor(receiver.first_name) }"
          >
            {{ receiver.first_name ? receiver.first_name.charAt(0).toUpperCase() : '?' }}
          </div>
  
          <div class="chat-user-info">
            <strong>{{ receiver.first_name }} {{ receiver.last_name }}</strong>
            <small v-if="receiver.last_seen">
              Last seen {{ formatLastSeen(receiver.last_seen) }}
            </small>
            <small v-else>Online</small>
          </div>
        </div>
  
        <div class="header-right">
          <button
            class="minimize-btn"
            @click.stop="toggleMinimize"
            :title="showChat ? 'Minimize' : 'Open chat'"
          >
            <span v-if="showChat">▾</span>
            <span v-else>▸</span>
          </button>
        </div>
      </div>
  
      <!-- Chat body -->
      <div class="chat-body" v-show="showChat">
        <!-- Messages -->
        <div class="chat-messages" id="messages">
          <div
            v-for="(group, date) in groupedMessagesOrdered"
            :key="date"
            class="date-group"
          >
            <div class="date-header">{{ formatDateHeader(date) }}</div>
  
            <div
              v-for="(msg, index) in group"
              :key="index"
              :class="['chat-message', isSender(msg) ? 'sent' : 'received']"
            >
              <div class="bubble">
                <div class="text">{{ msg.message }}</div>
              </div>
              <div class="meta">
                <span class="time">{{ formatTime(msg.created_at) }}</span>
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
        showChat: false,
      };
    },
    computed: {
      groupedMessages() {
        const groups = {};
        (this.messages || []).forEach((msg) => {
          let created = msg.created_at || msg.createdAt || msg.time || null;
          let dateKey;
          if (created) {
            const d = new Date(created);
            dateKey = isNaN(d)
              ? new Date().toISOString().split("T")[0]
              : d.toISOString().split("T")[0];
          } else {
            dateKey = new Date().toISOString().split("T")[0];
          }
          if (!groups[dateKey]) groups[dateKey] = [];
          groups[dateKey].push(msg);
        });
  
        Object.keys(groups).forEach((k) => {
          groups[k].sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
        });
  
        return groups;
      },
      groupedMessagesOrdered() {
        const entries = Object.entries(this.groupedMessages);
        entries.sort((a, b) => new Date(a[0]) - new Date(b[0]));
        return Object.fromEntries(entries);
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
        if (event?.message) {
          this.messages.push(event.message);
          this.scrollToBottom();
        }
      });
  
      // Load existing messages
      axios
        .get(`/profile/chat/${receiverId}`)
        .then((res) => {
          this.messages = Array.isArray(res.data) ? res.data : [];
          this.scrollToBottom();
        })
        .catch((err) => {
          console.error("Failed to load messages:", err);
        });
    },
    methods: {
      toggleMinimize() {
        this.showChat = !this.showChat;
        if (this.showChat) this.$nextTick(this.scrollToBottom);
      },
      isSender(msg) {
        const senderIdFields = ["sender_id", "user_id", "from_id", "from"];
        for (const f of senderIdFields) {
          if (msg[f] !== undefined) {
            return Number(msg[f]) === Number(this.userId);
          }
        }
        if (typeof msg.is_sender === "boolean") return msg.is_sender;
        return false;
      },
      sendMessage() {
        if (!this.newMessage.trim()) return;
  
        const nowIso = new Date().toISOString();
        const tempMessage = {
          id: "tmp-" + nowIso,
          message: this.newMessage,
          sender_id: this.userId,
          user_id: this.userId,
          created_at: nowIso,
        };
  
        this.messages.push(tempMessage);
        this.scrollToBottom();
  
        const payload = {
          receiver_id: this.receiver.id,
          message: this.newMessage,
        };
  
        const justSent = this.newMessage;
        this.newMessage = "";
        this.typing = false;
  
        axios
          .post("/profile/chat/send", payload)
          .then((res) => {
            if (res.data?.message && res.data.message.id) {
              const idx = this.messages.findIndex(
                (m) => m.id && String(m.id).startsWith("tmp-") && m.message === justSent
              );
              if (idx !== -1) {
                this.messages.splice(idx, 1, res.data.message);
              } else {
                this.messages.push(res.data.message);
              }
            }
            this.scrollToBottom();
          })
          .catch((err) => {
            console.error("Failed to send message:", err);
          });
      },
      formatTime(datetime) {
        if (!datetime) return "";
        const d = new Date(datetime);
        if (isNaN(d)) return "";
        return d.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
      },
      formatDateHeader(dateStr) {
        const date = new Date(dateStr + "T00:00:00");
        const today = new Date();
        const yesterday = new Date();
        yesterday.setDate(today.getDate() - 1);
  
        if (date.toDateString() === today.toDateString()) return "Today";
        if (date.toDateString() === yesterday.toDateString()) return "Yesterday";
  
        return date.toLocaleDateString(undefined, {
          weekday: "short",
          month: "short",
          day: "numeric",
        });
      },
      formatLastSeen(datetime) {
        const d = new Date(datetime);
        if (isNaN(d)) return "";
        return d.toLocaleString([], { weekday: "short", hour: "2-digit", minute: "2-digit" });
      },
      scrollToBottom() {
        this.$nextTick(() => {
          const container = document.getElementById("messages");
          if (container) container.scrollTop = container.scrollHeight + 100;
        });
      },
      avatarColor(name) {
        // Simple hash for color generation
        const colors = ["#E57373", "#64B5F6", "#81C784", "#BA68C8", "#FFB74D", "#4DB6AC"];
        if (!name) return "#9E9E9E";
        const code = name.charCodeAt(0);
        return colors[code % colors.length];
      },
      onImageError(event) {
        event.target.style.display = "none"; // hide broken img
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
    background: #f0f2f5;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
  }
  
  /* Header */
  .chat-header {
    display: flex;
    align-items: center;
    background: #075e54;
    color: white;
    padding: 8px 12px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    justify-content: space-between;
    cursor: pointer;
  }
  
  .header-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  /* Profile image or initial */
  .profile-img,
  .profile-initial {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    flex-shrink: 0;
  }
  .profile-img {
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.12);
  }
  .profile-initial {
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 18px;
    background: #888;
  }
  
  .chat-user-info strong {
    display: block;
    font-size: 14px;
    line-height: 1;
  }
  .chat-user-info small {
    font-size: 12px;
    opacity: 0.9;
    display: block;
    margin-top: 2px;
  }
  
  /* Minimize button */
  .minimize-btn {
    background: transparent;
    border: none;
    color: white;
    font-size: 18px;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    cursor: pointer;
  }
  .minimize-btn:hover {
    background: rgba(255, 255, 255, 0.06);
  }
  
  /* Chat body */
  .chat-body {
    display: flex;
    flex-direction: column;
    transition: height 0.18s ease;
  }
  
  /* Messages */
  .chat-messages {
    max-height: 400px;
    overflow-y: auto;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  
  /* Date header */
  .date-header {
    text-align: center;
    color: #777;
    font-size: 12px;
    margin: 10px 0;
    font-weight: 500;
  }
  
  /* Messages */
  .chat-message {
    display: flex;
    flex-direction: column;
  }
  .received {
    align-items: flex-start;
  }
  .received .bubble {
    background: #e4e6eb;
    color: #050505;
    border-radius: 10px;
    padding: 10px 14px;
    max-width: 70%;
    word-wrap: break-word;
    font-size: 14px;
  }
  .sent {
    align-items: flex-end;
  }
  .sent .bubble {
    background: #0084ff;
    color: #fff;
    border-radius: 18px;
    padding: 10px 14px;
    max-width: 70%;
    word-wrap: break-word;
    font-size: 14px;
  }
  
  /* Timestamp below bubbles */
  .meta {
    font-size: 11px;
    color: #65676b;
    margin-top: 4px;
  }
  .received .meta {
    align-self: flex-start;
    margin-left: 10px;
  }
  .sent .meta {
    align-self: flex-end;
    margin-right: 10px;
  }
  
  /* Input */
  .chat-input {
    display: flex;
    align-items: center;
    padding: 10px;
    background: #fff;
    border-top: 1px solid rgba(0, 0, 0, 0.06);
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
    background: #0084ff;
    border: none;
    color: white;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    margin-left: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }
  .send-btn:hover {
    background: #0073e6;
  }
  
  @media (max-width: 420px) {
    .chat-box {
      width: 95%;
    }
    .bubble {
      max-width: 80%;
    }
  }
  </style>
