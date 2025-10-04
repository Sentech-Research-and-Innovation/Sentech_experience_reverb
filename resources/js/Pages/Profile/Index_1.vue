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

        <!-- Connection Status -->
        <div v-if="connectionStatus" class="connection-status mt-2">
          <el-alert 
            :title="connectionStatus" 
            :type="isConnected ? 'success' : 'error'" 
            :closable="false" 
          />
        </div>

        <!-- Action Buttons -->
        <div class="profile-actions mt-4">
          <el-button 
            type="primary" 
            @click="startCall(user.id)" 
            :disabled="!isConnected || isCallInProgress"
          >
            📞 {{ isCallInProgress ? 'Calling...' : 'Call' }}
          </el-button>
          <el-button type="info" @click="openLogs">📑 View Call Logs</el-button>
          <el-button type="warning" @click="testConnection" :loading="testingConnection">
            🔄 Test Connection
          </el-button>
        </div>

        <!-- Call Status -->
        <div v-if="callStatus" class="call-status mt-2">
          <el-alert :title="callStatus" type="info" :closable="false" />
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

  <!-- Connection Error Dialog -->
  <el-dialog v-model="connectionErrorVisible" title="Connection Error" width="400px">
    <p>Unable to establish real-time connection. This is required for call functionality.</p>
    <p><strong>Current Domain:</strong> {{ currentDomain }}</p>
    <p><strong>WebSocket Target:</strong> {{ websocketTarget }}</p>
    <div v-if="connectionDetails" class="connection-details">
      <p><strong>Connection Details:</strong></p>
      <pre>{{ connectionDetails }}</pre>
    </div>
    <p><strong>Possible solutions:</strong></p>
    <ul>
      <li>Check if Laravel Echo Server is running on port 6001</li>
      <li>Verify SSL certificate covers WebSocket connections</li>
      <li>Ensure port 6001 is open in firewall</li>
      <li>Check browser console for detailed error messages</li>
    </ul>
    <template #footer>
      <el-button @click="connectionErrorVisible = false">Close</el-button>
      <el-button type="primary" @click="reconnectEcho">Reconnect</el-button>
    </template>
  </el-dialog>
</template>

<script>
import { defineComponent, ref, onMounted, onBeforeUnmount, watch, nextTick, computed } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { UserFilled } from "@element-plus/icons-vue";
import echo from "@/bootstrap/echo";

class SimpleWebRTC {
  constructor() {
    this.peerConnection = null;
    this.localStream = null;
    this.remoteStream = null;
    this.isInitiator = false;
  }

  async initialize(initiator = false, onSignal = () => {}) {
    this.isInitiator = initiator;
    this.onSignal = onSignal;

    const configuration = {
      iceServers: [
        { urls: 'stun:stun.l.google.com:19302' }
      ]
    };

    this.peerConnection = new RTCPeerConnection(configuration);

    this.peerConnection.ontrack = (event) => {
      this.remoteStream = event.streams[0];
      if (this.onRemoteStream) {
        this.onRemoteStream(this.remoteStream);
      }
    };

    this.peerConnection.onicecandidate = (event) => {
      if (event.candidate) {
        this.onSignal({
          type: 'candidate',
          candidate: event.candidate
        });
      }
    };

    this.peerConnection.onconnectionstatechange = () => {
      console.log('Connection state:', this.peerConnection.connectionState);
    };

    return this.peerConnection;
  }

  async addLocalStream(stream) {
    this.localStream = stream;
    stream.getTracks().forEach(track => {
      this.peerConnection.addTrack(track, stream);
    });
  }

  async createOffer() {
    try {
      const offer = await this.peerConnection.createOffer();
      await this.peerConnection.setLocalDescription(offer);
      this.onSignal({
        type: 'offer',
        offer: offer
      });
    } catch (error) {
      console.error('Error creating offer:', error);
      throw error;
    }
  }

  async handleOffer(offer) {
    try {
      await this.peerConnection.setRemoteDescription(offer);
      const answer = await this.peerConnection.createAnswer();
      await this.peerConnection.setLocalDescription(answer);
      this.onSignal({
        type: 'answer',
        answer: answer
      });
    } catch (error) {
      console.error('Error handling offer:', error);
      throw error;
    }
  }

  async handleAnswer(answer) {
    try {
      await this.peerConnection.setRemoteDescription(answer);
    } catch (error) {
      console.error('Error handling answer:', error);
      throw error;
    }
  }

  async handleCandidate(candidate) {
    try {
      await this.peerConnection.addIceCandidate(candidate);
    } catch (error) {
      console.error('Error handling candidate:', error);
    }
  }

  destroy() {
    if (this.localStream) {
      this.localStream.getTracks().forEach(track => track.stop());
    }
    if (this.peerConnection) {
      this.peerConnection.close();
    }
    this.localStream = null;
    this.remoteStream = null;
    this.peerConnection = null;
  }
}

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

    const currentUserId = Number(window.userId || 1);
    const logsVisible = ref(false);
    const callLogs = ref([]);
    const incomingVisible = ref(false);
    const incomingCall = ref(null);
    const isCallInProgress = ref(false);
    const callStatus = ref('');
    const connectionStatus = ref('Connecting...');
    const isConnected = ref(false);
    const connectionErrorVisible = ref(false);
    const testingConnection = ref(false);
    const connectionDetails = ref('');

    const localStream = ref(null);
    const remoteStream = ref(null);
    const webrtc = ref(new SimpleWebRTC());

    // Domain information
    const currentDomain = computed(() => window.location.hostname);
    const websocketTarget = computed(() => {
      return `wss://${currentDomain.value}:6001`;
    });

    let channel = null;
    let connectionCheckInterval = null;

    // Check Echo connection status
    function checkEchoConnection() {
      try {
        const socket = echo.connector.socket;
        const connected = socket && socket.id;
        isConnected.value = !!connected;
        
        // Build connection details for debugging
        connectionDetails.value = JSON.stringify({
          socketId: socket?.id || 'No socket ID',
          connected: !!connected,
          hostname: currentDomain.value,
          socketState: socket?.readyState || 'No socket',
          transport: socket?.transport?.name || 'No transport'
        }, null, 2);
        
        connectionStatus.value = connected 
          ? `✅ Connected to ${currentDomain.value}` 
          : `❌ Disconnected from ${currentDomain.value}`;
        
        if (!connected) {
          connectionErrorVisible.value = true;
        }
        
        return connected;
      } catch (error) {
        console.error('Error checking Echo connection:', error);
        isConnected.value = false;
        connectionStatus.value = 'Error checking connection';
        connectionDetails.value = `Error: ${error.message}`;
        return false;
      }
    }

    function initializeEchoConnection() {
      try {
        console.log('🔄 Initializing Echo connection for domain:', currentDomain.value);
        
        const socket = echo.connector.socket;
        if (socket) {
          socket.on('connect', () => {
            console.log('✅ Echo connected successfully to:', currentDomain.value);
            isConnected.value = true;
            connectionStatus.value = `✅ Connected to ${currentDomain.value}`;
            connectionErrorVisible.value = false;
            subscribeToChannels();
          });

          socket.on('disconnect', (reason) => {
            console.log('❌ Echo disconnected from:', currentDomain.value, 'Reason:', reason);
            isConnected.value = false;
            connectionStatus.value = `❌ Disconnected from ${currentDomain.value}`;
          });

          socket.on('error', (error) => {
            console.error('Echo connection error:', error);
            isConnected.value = false;
            connectionStatus.value = `❌ Connection error to ${currentDomain.value}`;
            connectionErrorVisible.value = true;
          });
        } else {
          console.error('No Echo socket available');
          connectionStatus.value = 'No Echo socket available';
        }

        // Initial connection check
        setTimeout(() => {
          if (!checkEchoConnection()) {
            console.warn('Echo not connected after initialization to', currentDomain.value);
            connectionStatus.value = `⚠️ Failed to connect to ${currentDomain.value}`;
          }
        }, 3000);

      } catch (error) {
        console.error('Failed to initialize Echo connection:', error);
        connectionStatus.value = `❌ Failed to initialize connection to ${currentDomain.value}`;
        connectionErrorVisible.value = true;
      }
    }

    function subscribeToChannels() {
      try {
        if (!echo.connector.socket || !echo.connector.socket.id) {
          console.error('Echo socket not available for subscription');
          return;
        }

        // Unsubscribe from previous channel if exists
        if (channel) {
          channel.stopListening();
        }

        channel = echo.private(`calls.${currentUserId}`);

        channel.listen("CallOfferEvent", (e) => {
          console.log("📥 Incoming offer:", e);
          if (!isCallInProgress.value) {
            incomingCall.value = e;
            incomingVisible.value = true;
          }
        });

        channel.listen("CallAnswerEvent", (e) => {
          console.log("📥 Incoming answer:", e);
          webrtc.value.handleAnswer(e.answer);
          callStatus.value = 'Call connected';
        });

        channel.listen("CallCandidateEvent", (e) => {
          console.log("📥 Incoming candidate:", e);
          if (e.candidate) {
            webrtc.value.handleCandidate(e.candidate);
          }
        });

        console.log('✅ Successfully subscribed to call channels on', currentDomain.value);

      } catch (error) {
        console.error('❌ Failed to subscribe to channels:', error);
        connectionStatus.value = `❌ Failed to subscribe to channels on ${currentDomain.value}`;
      }
    }

    async function testConnection() {
      testingConnection.value = true;
      try {
        // Test WebSocket connection
        const isEchoConnected = checkEchoConnection();
        
        // Test API connection
        await window.axios.get('/api/test-connection');
        
        if (isEchoConnected) {
          connectionStatus.value = `✅ All connections working properly on ${currentDomain.value}`;
        } else {
          connectionStatus.value = `⚠️ API connected but WebSocket failed on ${currentDomain.value}`;
        }
      } catch (error) {
        console.error('Connection test failed:', error);
        connectionStatus.value = `❌ Connection test failed for ${currentDomain.value}`;
      } finally {
        testingConnection.value = false;
      }
    }

    function reconnectEcho() {
      try {
        console.log('🔄 Attempting to reconnect Echo to', currentDomain.value);
        
        const socket = echo.connector.socket;
        if (socket) {
          socket.connect();
        }
        
        setTimeout(() => {
          const reconnected = checkEchoConnection();
          if (!reconnected) {
            connectionStatus.value = `❌ Reconnection to ${currentDomain.value} failed`;
          } else {
            connectionStatus.value = `✅ Reconnected to ${currentDomain.value}`;
          }
        }, 3000);
      } catch (error) {
        console.error('Reconnection failed:', error);
        connectionStatus.value = `❌ Reconnection to ${currentDomain.value} failed`;
      }
    }

    // ... rest of the WebRTC and component methods remain the same
    // (initializeWebRTC, handleWebRTCSignal, cleanupCall, openLogs, startCall, acceptCall, declineCall)

    // Initialize WebRTC
    function initializeWebRTC() {
      webrtc.value.onRemoteStream = (stream) => {
        remoteStream.value = stream;
      };

      webrtc.value.onSignal = (data) => {
        handleWebRTCSignal(data);
      };
    }

    function handleWebRTCSignal(data) {
      if (!isConnected.value) {
        alert('Cannot send call signal: Not connected to server');
        return;
      }

      const targetUserId = webrtc.value.isInitiator ? props.user.id : incomingCall.value?.from;

      if (data.type === 'offer') {
        window.axios.post("/call/offer", {
          from: currentUserId,
          to: targetUserId,
          offer: data.offer,
        }).catch(error => {
          console.error('Failed to send offer:', error);
          alert('Failed to send call offer');
        });
      } else if (data.type === 'answer') {
        window.axios.post("/call/answer", {
          from: currentUserId,
          to: targetUserId,
          answer: data.answer,
        }).catch(error => {
          console.error('Failed to send answer:', error);
        });
      } else if (data.type === 'candidate') {
        window.axios.post("/call/candidate", {
          from: currentUserId,
          to: targetUserId,
          candidate: data.candidate,
        }).catch(error => {
          console.error('Failed to send candidate:', error);
        });
      }
    }

    // Attach audio streams
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
      initializeWebRTC();
      
      // Wait a bit for Echo to initialize, then set up connection monitoring
      setTimeout(() => {
        initializeEchoConnection();
        
        // Set up periodic connection checking
        connectionCheckInterval = setInterval(checkEchoConnection, 10000);
      }, 1000);
    });

    onBeforeUnmount(() => {
      cleanupCall();
      channel?.stopListening();
      if (connectionCheckInterval) {
        clearInterval(connectionCheckInterval);
      }
    });

    function cleanupCall() {
      webrtc.value.destroy();
      localStream.value = null;
      remoteStream.value = null;
      isCallInProgress.value = false;
      callStatus.value = '';
    }

    function openLogs() {
      logsVisible.value = true;
    }

    async function startCall(targetUserId) {
      if (!isConnected.value) {
        connectionErrorVisible.value = true;
        alert('Cannot make call: Not connected to server. Please check connection and try again.');
        return;
      }

      try {
        cleanupCall();
        isCallInProgress.value = true;
        callStatus.value = 'Starting call...';

        // Get user media
        localStream.value = await navigator.mediaDevices.getUserMedia({
          audio: {
            echoCancellation: true,
            noiseSuppression: true,
            autoGainControl: true
          }
        });

        // Initialize WebRTC
        await webrtc.value.initialize(true);
        await webrtc.value.addLocalStream(localStream.value);
        await webrtc.value.createOffer();

        callLogs.value.push({
          type: "Outgoing",
          name: props.user.first_name,
          time: new Date().toLocaleString(),
        });

        callStatus.value = 'Calling...';

      } catch (err) {
        console.error("Call error:", err);
        alert("Failed to start call: " + err.message);
        cleanupCall();
      }
    }

    async function acceptCall() {
      const { from, offer, from_name } = incomingCall.value;
      
      try {
        isCallInProgress.value = true;
        callStatus.value = 'Accepting call...';

        // Get user media
        localStream.value = await navigator.mediaDevices.getUserMedia({
          audio: {
            echoCancellation: true,
            noiseSuppression: true,
            autoGainControl: true
          }
        });

        // Initialize WebRTC and handle offer
        await webrtc.value.initialize(false);
        await webrtc.value.addLocalStream(localStream.value);
        await webrtc.value.handleOffer(offer);

        callLogs.value.push({
          type: "Incoming-Accepted",
          name: from_name,
          time: new Date().toLocaleString(),
        });

        incomingVisible.value = false;
        incomingCall.value = null;
        callStatus.value = 'Call connected';

      } catch (err) {
        console.error("Accept call error:", err);
        alert("Failed to accept call: " + err.message);
        cleanupCall();
        incomingVisible.value = false;
        incomingCall.value = null;
      }
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
      isCallInProgress,
      callStatus,
      connectionStatus,
      isConnected,
      connectionErrorVisible,
      testingConnection,
      testConnection,
      reconnectEcho,
      currentDomain,
      websocketTarget,
      connectionDetails,
      user: props.user,
    };
  },
});
</script>

<style scoped>
/* Your existing styles remain the same */
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
  flex-wrap: wrap;
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
.call-status, .connection-status {
  margin-top: 10px;
}
.connection-details {
  background: #f5f5f5;
  padding: 10px;
  border-radius: 4px;
  margin: 10px 0;
  font-size: 12px;
}
.connection-details pre {
  margin: 0;
  white-space: pre-wrap;
}
</style>
