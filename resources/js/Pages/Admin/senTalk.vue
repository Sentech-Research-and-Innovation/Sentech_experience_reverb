<template>
  <div class="sentalk-card">
    <div class="sentalk-header">
      <h2>{{ latest.title }}</h2>
    </div>

    <!-- PDF Viewer -->
    <iframe
      v-if="latest.pdf_path"
      :src="`/storage/${latest.pdf_path}#toolbar=0`"
      width="100%"
      height="600"
    ></iframe>

    <!-- Upload New Button -->
    <div>
      <label for="upload" class="btn">⬆ Upload New</label>
      <input
        type="file"
        id="upload"
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
      latest: {
        title: "No PDF uploaded yet",
        pdf_path: null,
      },
    };
  },
  methods: {
    async uploadFile(event) {
      const file = event.target.files[0];
      if (!file) return;

      let formData = new FormData();
      formData.append("pdf", file);

      try {
        const res = await axios.post("/admin/sentalk/upload", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });

        // Update preview with uploaded PDF
        this.latest = {
          title: res.data.title,
          pdf_path: res.data.pdf_path,
        };
      } catch (error) {
        console.error("Upload failed:", error);
      }
    },
  },
};
</script>
