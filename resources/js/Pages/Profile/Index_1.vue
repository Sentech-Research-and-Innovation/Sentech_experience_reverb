<template>
  <Head :title="'Profile'"><title>Profile</title></Head>

  <div class="page-wrapper">
    <div class="col-12 px-0 mx-0 profile-container">
      <!-- Background Cover Image -->
      <div
        class="cover-image"
        :style="{ backgroundImage: `url('${user.cover_photo_url || defaultCover}')` }"
      ></div>

      <div class="profile-content px-4">
        <!-- Profile Picture -->
        <div class="profile-picture-container">
          <el-avatar
            :src="user.profile_photo_url || defaultProfile"
            :icon="!user.profile_photo_url ? UserFilled : ''"
            class="blue-profile-image"
          />
        </div>

        <!-- Profile Info -->
        <div class="profile-info mt-4">
          <p class="profile-name">{{ user.first_name }} {{ user.last_name }}</p>
          <p class="profile-title">
            {{ user.roles[0]?.name }} at {{ user.company?.company_name }}
          </p>
          <div class="profile-contact mt-1">
            <span class="contact-info">
              {{ user.email || 'Email not provided' }},
              {{ user.phoneNumber || 'Phone not provided' }}
            </span>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="profile-actions mt-4">
          <el-button type="primary" @click="startCall(user.id)">📞 Call</el-button>
          <el-button type="info" @click="openLogs">📑 View Call Logs</el-button>
        </div>

        <!-- Audio streams -->
        <audio id="localAudio" autoplay muted></audio>
        <audio id="remoteAudio" autoplay></audio>
      </div>
    </div>
  </div>

  <!-- Call Logs Dialog -->
  <el-dialog v-model="logsVisible" title="Call Logs" width="400px">
    <ul class="call-logs">
      <li v-for="(log, index) in callLogs" :key="index">
        <span>{{ log.type }} with {{ log.name }}</span>
        <span class="time">{{ log.time }}</span>
      </li>
    </ul>
    <template #footer>
      <el-button @click="logsVisible = false">Close</el-button>
    </template>
  </el-dialog>

  <!-- Incoming Call Dialog -->
  <el-dialog v-model="incomingVisible" title="Incoming Call" width="300px" align-center>
    <p>User {{ incomingCall?.from_name }} is calling you...</p>
    <div class="incoming-actions">
      <el-button type="success" @click="acceptCall">✅ Accept</el-button>
      <el-button type="danger" @click="declineCall">❌ Decline</el-button>
    </div>
  </el-dialog>
</template>

<script>
import { defineComponent, ref, onMounted, onBeforeUnmount, watch, nextTick } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { UserFilled } from "@element-plus/icons-vue";
import echo from "@/bootstrap/echo";
import Peer from "simple-peer";

export default defineComponent({
  name: "ProfileView",
  props: {
    user: { type: Object, required: true },
  },
  setup(props) {
    const defaultCover =
      "https://images.unsplash.com/photo-1517816743773-6e0fd518b4a6?q=80&w=1920&fit=crop";
    const defaultProfile =
      "https://images.unsplash.com/photo-1603415526960-f8f0a2b52f75?q=80&w=200&fit=crop";

    // Assume Laravel passes authenticated user ID into window.userId
    const currentUserId = Number(window.userId || 1);

    const logsVisible = ref(false);
    const callLogs = ref([]);
    const incomingVisible = ref(false);
    const incomingCall = ref(null);

    const localStream = ref(null);
    const remoteStream = ref(null);
    const peerRef = ref(null);

    let channel = null;

    // Attach audio
    watch(localStream, async (stream) => {
      await nextTick();
      const el = document.getElementById("localAudio");
      if (el && stream) el.srcObject = stream;
    });
    watch(remoteStream, async (stream) => {
      await nextTick();
      const el = document.getElementById("remoteAudio");
      if (el && stream) el.srcObject = stream;
    });

    onMounted(() => {
      // Subscribe to signaling channel
      channel = echo
        .private(`calls.${currentUserId}`)
        .listen("CallOfferEvent", (e) => {
          console.log("📥 Incoming offer:", e);
          incomingCall.value = e;
          incomingVisible.value = true;
        })
        .listen("CallAnswerEvent", (e) => {
          if (peerRef.value) peerRef.value.signal(e.answer);
        })
        .listen("CallCandidateEvent", (e) => {
          if (peerRef.value && e.candidate) peerRef.value.signal(e.candidate);
        });
    });

    onBeforeUnmount(() => {
      channel?.stopListening();
    });

    function openLogs() {
      logsVisible.value = true;
    }

    function makePeer(initiator, targetUserId) {
      const peer = new Peer({ initiator, trickle: true });

      peer.on("signal", (data) => {
        if (data.type === "offer") {
          window.axios.post("/call/offer", {
            from: currentUserId,
            to: targetUserId,
            offer: data,
          });
        } else if (data.type === "answer") {
          window.axios.post("/call/answer", {
            from: currentUserId,
            to: targetUserId,
            answer: data,
          });
        } else if (data.candidate) {
          window.axios.post("/call/candidate", {
            from: currentUserId,
            to: targetUserId,
            candidate: data,
          });
        }
      });

      peer.on("stream", (stream) => {
        remoteStream.value = stream;
      });

      peerRef.value = peer;
      return peer;
    }

    async function startCall(targetUserId) {
      try {
        localStream.value = await navigator.mediaDevices.getUserMedia({ audio: true });
      } catch (err) {
        console.error("Mic error:", err);
        return;
      }
      const peer = makePeer(true, targetUserId);
      localStream.value.getTracks().forEach((t) => peer.addTrack(t, localStream.value));
      callLogs.value.push({
        type: "Outgoing",
        name: props.user.first_name,
        time: new Date().toLocaleString(),
      });
    }

    async function acceptCall() {
      const { from, offer, from_name } = incomingCall.value;
      try {
        localStream.value = await navigator.mediaDevices.getUserMedia({ audio: true });
      } catch (err) {
        console.error("Mic error:", err);
        return;
      }
      const peer = makePeer(false, from);
      localStream.value.getTracks().forEach((t) => peer.addTrack(t, localStream.value));
      peer.signal(offer);

      callLogs.value.push({
        type: "Incoming-Accepted",
        name: from_name,
        time: new Date().toLocaleString(),
      });

      incomingVisible.value = false;
      incomingCall.value = null;
    }

    function declineCall() {
      callLogs.value.push({
        type: "Incoming-Declined",
        name: incomingCall.value?.from_name,
        time: new Date().toLocaleString(),
      });
      incomingVisible.value = false;
      incomingCall.value = null;
    }

    return {
      UserFilled,
      defaultCover,
      defaultProfile,
      logsVisible,
      callLogs,
      openLogs,
      startCall,
      acceptCall,
      declineCall,
      incomingVisible,
      incomingCall,
       user: props.user,
    };
  },
});
</script>

<style scoped>
.page-wrapper {
  display: flex;
  justify-content: center;
  padding: 20px;
}
.profile-container {
  position: relative;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 1px 5px rgba(0,0,0,0.2);
  width: 85%;
  max-width: 1000px;
}
.cover-image {
  height: 200px;
  border-top-left-radius: 8px;
  border-top-right-radius: 8px;
  background-size: cover;
  background-position: center;
}
.profile-picture-container {
  position: absolute;
  top: -75px;
  left: 20px;
  border-radius: 50%;
}
.blue-profile-image {
  width: 150px;
  height: 150px;
  border: 4px solid #fff;
}
.profile-info {
  padding-top: 90px;
}
.profile-actions {
  margin-top: 20px;
  display: flex;
  gap: 10px;
}
.call-logs {
  list-style: none;
  padding: 0;
}
.call-logs li {
  display: flex;
  justify-content: space-between;
  border-bottom: 1px solid #eee;
  padding: 8px 0;
}
.time {
  font-size: 12px;
  color: #888;
}
.incoming-actions {
  display: flex;
  justify-content: space-around;
  margin-top: 15px;
}
</style>
