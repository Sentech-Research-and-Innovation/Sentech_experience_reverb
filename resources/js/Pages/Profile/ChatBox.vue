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
        v-for="(group, date) in groupedMessagesOrdered"
        :key="date"
        class="date-group"
      >
        <div class="date-header">{{ formatDateHeader(date) }}</div>

        <div
          v-for="(msg, index) in group"
          :key="index"
          :class="[
            'chat-message',
            isSender(msg) ? 'sent' : 'received',
          ]"
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
    // groups object keyed by date string (YYYY-MM-DD)
    groupedMessages() {
      const groups = {};
      (this.messages || []).forEach((msg) => {
        // handle different timestamp formats gracefully
        let created = msg.created_at || msg.createdAt || msg.time || null;
        let dateKey;
        if (created) {
          // Normalize to YYYY-MM-DD
          const d = new Date(created);
          if (isNaN(d)) {
            // fallback: if created is not a valid date, use today
            const now = new Date();
            dateKey = now.toISOString().split("T")[0];
          } else {
            dateKey = d.toISOString().split("T")[0];
          }
        } else {
          dateKey = new Date().toISOString().split("T")[0];
        }

        if (!groups[dateKey]) groups[dateKey] = [];
        groups[dateKey].push(msg);
      });

      // Ensure each day's messages are sorted by time ascending
      Object.keys(groups).forEach((k) => {
        groups[k].sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
      });

      return groups;
    },

    // order groups by date ascending (so older first, newer last)
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

    // Listen for new messages (server broadcast)
    Echo.channel(channelName).listen("MessageSent", (event) => {
      // event.message expected from backend
      if (event?.message) {
        this.messages.push(event.message);
        this.scrollToBottom();
      }
    });

    // Initial load
    axios
      .get(`/profile/chat/${receiverId}`)
      .then((res) => {
        // Expect array of messages with fields: message, sender_id (or user_id), created_at
        this.messages = Array.isArray(res.data) ? res.data : [];
        this.scrollToBottom();
      })
      .catch((err) => {
        console.error("Failed to load messages:", err);
      });
  },
  methods: {
    isSender(msg) {
      // robust check for sender id field
      const senderIdFields = ["sender_id", "user_id", "from_id", "from"];
      for (const f of senderIdFields) {
        if (msg[f] !== undefined) {
          return Number(msg[f]) === Number(this.userId);
        }
      }
      // fallback: if message has 'is_sender' boolean
      if (typeof msg.is_sender === "boolean") return msg.is_sender;
      return false;
    },

    sendMessage() {
      if (!this.newMessage.trim()) return;

      // local immediate push so user sees the message instantly
      const nowIso = new Date().toISOString();
      const tempMessage = {
        id: "tmp-" + nowIso,
        message: this.newMessage,
        // choose sender field your backend expects; include few so UI checks succeed
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

      // clear input immediately (UX pattern)
      const justSent = this.newMessage;
      this.newMessage = "";
      this.typing = false;

      axios
        .post("/profile/chat/send", payload)
        .then((res) => {
          // optionally replace the temp message id with the real id returned by server
          if (res.data?.message && res.data.message.id) {
            // find temp msg by matching text + sender + nearest timestamp
            const idx = this.messages.findIndex(
              (m) => m.id && String(m.id).startsWith("tmp-") && m.message === justSent
            );
            if (idx !== -1) {
              this.messages.splice(idx, 1, res.data.message);
            } else {
              // if not found, ensure server message is present
              this.messages.push(res.data.message);
            }
          }
          this.scrollToBottom();
        })
        .catch((err) => {
          console.error("Failed to send message:", err);
          // Optionally mark the last message failed (add property) or show toast
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

      return date.toLocaleDateString(undefined, { weekday: "short", month: "short", day: "numeric" });
    },

    scrollToBottom() {
      this.$nextTick(() => {
        const container = document.getElementById("messages");
        if (container) container.scrollTop = container.scrollHeight + 100;
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
background: #f0f2f5;
font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
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

/* message list area */
.chat-messages {
max-height: 400px;
overflow-y: auto;
padding: 12px;
display: flex;
flex-direction: column;
gap: 8px;
}

.date-header {
text-align: center;
color: #777;
font-size: 12px;
margin: 10px 0;
font-weight: 500;
}

/* each message */
.chat-message {
display: flex;
flex-direction: column;
}

/* receiver (left side) */
.received {
align-items: flex-start;
}
.received .bubble {
background: #e4e6eb;
color: #050505;
border-radius: 18px;
padding: 10px 14px;
max-width: 70%;
word-wrap: break-word;
font-size: 14px;
position: relative;
}

.received .meta {
    align-self: flex-start; 
    margin-top: 2px;
    margin-left: 10px;
  }

/* sender (right side) */
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
position: relative;
}

.sent .meta {
    align-self: flex-end; 
    margin-top: 2px;
    margin-right: 10px;
  }

/* timestamp below bubble */
.meta {
/* margin-top: 4px; */
font-size: 11px;
color: #65676b;
}
.sent .meta,
.received .meta {
align-self: flex-end;
}
.time {
font-size: 11px;
color: #65676b;
}

/* input section */
.chat-input {
display: flex;
align-items: center;
padding: 10px;
background: #fff;
border-top: 1px solid rgba(0,0,0,0.06);
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
.chat-box { width: 95%; }
.bubble { max-width: 80%; }
}
</style>

