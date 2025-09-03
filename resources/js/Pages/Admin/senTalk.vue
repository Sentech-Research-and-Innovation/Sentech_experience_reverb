<template>
  <div class="sentalk-card">
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
      :src="`/storage/${latest.pdf_path}#toolbar=0`"
      width="100%"
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

    <!-- Older Editions -->
    <div class="older-editions">
      <h3>Older Editions</h3>
      <ul>
        <li v-for="edition in editions" :key="edition.id">
          <a href="javascript:void(0)" @click="view(edition)">
            {{ edition.title }} ({{ edition.created_at }})
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      latest: {
        title: "SenTalk August Edition 2025",
        creator: "MachabaL",
        number_views: 109,
        number_downloads: 23,
        number_likes: 3,
        created_at: "18 Aug 2025",
        pdf_path: "sentalk_pdfs/sample.pdf", // fallback sample
      },
      editions: [],
    };
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

        // Update preview with uploaded PDF
        this.latest = {
          title: res.data.title || file.name,
          creator: "You",
          number_views: 0,
          number_downloads: 0,
          number_likes: 0,
          created_at: new Date().toLocaleDateString(),
          pdf_path: res.data.pdf_path,
        };

        // Add new edition to the list
        this.editions.unshift(this.latest);
      } catch (err) {
        console.error("Upload failed:", err);
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
  background: #626AEF;
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
  color: #626AEF;
  text-decoration: none;
}
.older-editions a:hover {
  text-decoration: underline;
}
</style>
