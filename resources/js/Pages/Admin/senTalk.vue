<template> 
  <div class="sentalk-card">

    <!-- Header Row with Search + Download -->
    <div class="top-bar">
      <!-- Search -->
      <div class="search-container">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search by name"
          class="search-input"
          @keyup.enter="searchPdf"
        />
        <button class="btn btn-search" @click="searchPdf">Search</button>
        <button v-if="searchQuery" class="btn btn-clear" @click="clearSearch">
          Clear
        </button>
      </div>

      <!-- Download -->
      <a
        v-if="latest && latest.id"
        :href="`/sentalk/download/${latest.id}`"
        class="btn btn-download"
      >
        Download
      </a>
    </div>

    <!-- Latest Edition -->
    <div v-if="latest">
      <!-- Title + Stats -->
      <div class="title-row">
        <h2 class="pdf-title">{{ latest.title.replace('.pdf', '') }}</h2>
        <div class="stats">
          {{ latest.number_views }} views ·
          {{ latest.number_downloads }} downloads ·
          {{ latest.number_likes }} likes
        </div>
      </div>

      <!-- PDF Preview -->
      <iframe
        v-if="latest.pdf_path"
        :src="`/storage/${latest.pdf_path}#toolbar=0&view=FitH&v=${Date.now()}`"
      ></iframe>

      <!-- Upload Button -->
      <div class="actions">
        <button class="btn btn-upload" @click="triggerFileInput">Upload New</button>
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
            {{ edition.title.replace('.pdf','') }} ({{ edition.created_at }})
          </a>
        </li>
      </ul>
    </div>

    <!-- Empty state -->
    <div v-if="!latest && !editions.length" class="empty">
      <p>No editions available. Upload a PDF to get started.</p>
      <button class="btn btn-upload" @click="triggerFileInput">Upload First PDF</button>
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
        searchQuery: "",
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
        const res = await axios.post("/sentalk/upload", formData);

          if (res.data.success) {
            await this.fetchData();
        }else{
      alert("❌ Upload failed: " + (res.data.message || "Unknown error"));
    }
      } catch (err) {
        console.error("Upload failed:", err);
          alert("❌ Upload failed. Check logs.");
      }
    },

    async fetchData(search = "") {
      try {
        const res = await axios.get("/sentalk", {
          params: { search },
        });
        this.latest = res.data.latest;
        this.editions = res.data.editions;

        if (!this.latest) {
          alert("❌ No PDF found for your search!");
        this.clearSearch();
        }
          
      } catch (err) {
        console.error("Failed to fetch editions:", err);
      }
    },

    
    searchPdf() {
      if (!this.searchQuery.trim()) {
        this.fetchData(); // reload all if search empty
      } else {
        this.fetchData(this.searchQuery);
      }
    },


    clearSearch() {
      this.searchQuery = "";
      this.fetchData(); // reload original list
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
  box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
  border-radius: 0; /* square corners ONLY for outer container */
}

/* Top bar with search + download */
.top-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
  margin-top: 15px;
}

/* Title + stats row */
.title-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 10px 0 15px 0;
}
.pdf-title {
  font-size: 20px;
  font-weight: bold;
  margin: 0;
  color: #333;
}
.stats {
  font-size: 14px;
  color: #555;
}

/* Search bar */
.search-container {
  display: flex;
  align-items: center;
  gap: 6px;
}
.search-input {
  width: 220px;
  padding: 6px 10px;
  border: 1px solid #ccc;
  font-size: 14px;
  border-radius: 6px; /* keep rounded */
  height: 40px;
}


/* .btn {
  padding: 8px 20px;         
  font-size: 14px;
  font-weight: bold;         
  cursor: pointer;
  border: none;
  height: 40px;
  border-radius: 8px; 
} */

.btn {
  display: inline-flex;      /* Makes sure content is vertically aligned */
  align-items: center;       /* Vertically center the text/icon */
  justify-content: center;   /* Center horizontally */
  padding: 8px 20px;
  font-size: 14px;
  font-weight: bold;
  cursor: pointer;
  border: none;
  height: 40px;
  border-radius: 8px;
  text-decoration: none;     /* Remove underline for <a> buttons */
}



.btn-search,
.btn-download,
.btn-upload {
  background-color: #144f9f;
  color: #fff !important;
}
.btn-search:hover,
.btn-download:hover,
.btn-upload:hover {
  background-color: #0f3c7a;
}
.btn-clear {
  background: #e74c3c;
  color: #fff !important;
}
.btn-clear:hover {
  background: #c0392b;
}

/* Iframe */
iframe {
  width: 90%;
  height: 600px;
  border: 1px solid #ccc;
  margin: 0 auto 15px auto;  /* top: 0, right: auto, bottom: 10px, left: auto */
  display: block;
}

/* Older editions */
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
  color: #144f9f;
  text-decoration: none;
}
.older-editions a:hover {
  text-decoration: underline;
}
</style>
