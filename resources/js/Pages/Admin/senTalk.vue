<template>
  <div class="sentalk-card">
    <!-- Show latest edition if exists -->
    <div v-if="latest">
      <div class="sentalk-header">
        <h2>{{ latest.title }}</h2>
        <span class="stats">
          {{ latest.number_views }} views ·
          {{ latest.number_downloads }} downloads ·
          {{ latest.number_likes }} likes
        </span>
      </div>

      <!-- PDF Viewer -->
    <iframe
      v-if="latest.pdf_path"
      :src="`/storage/${latest.pdf_path}#toolbar=0&v=${Date.now()}`"
      width="90%"
      height="600"
    ></iframe>


      <!-- Action Buttons -->
      <div>
        <a
          v-if="latest.pdf_path"
          :href="`/storage/${latest.pdf_path}`"
          class="btn"
          download
        >
          ⬇ Download PDF
        </a>

        <!-- Upload New -->
        <button class="btn" @click="triggerFileInput">⬆ Upload New</button>
        <input
          type="file"
          ref="fileInput"
          accept="application/pdf"
          style="display:none"
          @change="uploadFile"
        />
      </div>
    </div>

    <!-- Older Editions -->
    <div class="older-editions" v-if="editions.length">
      <h3>Older Editions</h3>
      <ul>
        <li v-for="edition in editions" :key="edition.id">
          <a href="javascript:void(0)" @click="view(edition)">
            {{ edition.title }} ({{ edition.created_at }})
          </a>
        </li>
      </ul>
    </div>

    <!-- Empty state -->
    <div v-if="!latest && !editions.length" class="empty">
      <p>No editions available. Upload a PDF to get started.</p>
      <button class="btn" @click="triggerFileInput">⬆ Upload First PDF</button>
      <input
        type="file"
        ref="fileInput"
        accept="application/pdf"
        style="display:none"
        @change="uploadFile"
      />
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      latest: null,
      editions: [],
    };
  },

  async mounted() {
    await this.fetchData();
  },

  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },

    async uploadFile(event) {
      const file = event.target.files[0];
      if (!file) return;

      let formData = new FormData();
      formData.append("pdf", file);

      try {
        const res = await axios.post("/sentalk/upload", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });

          if (res.data.success) {
              this.latest = res.data.latest;

            // Refresh from DB after upload
            await this.fetchData();
        }
      } catch (err) {
        console.error("Upload failed:", err);
      }
    },

    async fetchData() {
      try {
        const res = await axios.get("/sentalk");
        this.latest = res.data.latest;
        this.editions = res.data.editions;
      } catch (err) {
        console.error("Failed to fetch editions:", err);
      }
    },

    view(edition) {
      this.latest = edition;
    },
  },
};
</script>

<style scoped>
.sentalk-card {
  max-width: 1000px;
  margin: 20px auto;
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
}
.sentalk-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}
.sentalk-header h2 {
  margin: 0;
  color: #444;
}
.stats {
  font-size: 14px;
  color: #777;
}
iframe {
  width: 100%;
  height: 600px;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-bottom: 15px;
}
.btn {
  display: inline-block;
  margin-right: 10px;
  padding: 10px 18px;
  background: #626aef;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-size: 14px;
  transition: background 0.3s;
  cursor: pointer;
}
.btn:hover {
  background: #4a52c0;
}
.older-editions {
  margin-top: 20px;
}
.older-editions h3 {
  margin-bottom: 10px;
  font-size: 18px;
  color: #444;
}
.older-editions ul {
  list-style: none;
  padding: 0;
}
.older-editions li {
  margin: 5px 0;
}
.older-editions a {
  color: #626aef;
  text-decoration: none;
}
.older-editions a:hover {
  text-decoration: underline;
}
.empty {
  text-align: center;
  padding: 40px;
  color: #777;
}
</style>
