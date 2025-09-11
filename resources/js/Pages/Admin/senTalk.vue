<template> 
  <div class="sentalk-card">

    <!-- Header Row with Search + Download -->
    <div class="top-bar">
      <!-- Search -->
      <div class="search-container">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search by title"
          class="search-input"
          @keyup.enter="searchPdf"
        />
        <button class="btn btn-search" @click="searchPdf">Search</button>
        <button v-if="searchQuery" class="btn btn-clear" @click="clearSearch">
          Clear
        </button>
      </div>

      <!-- provide feedback -->
      <div class="provide-feedback" @click="openFeedbackDialog">
        <span>Provide Feedback</span>
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
        <div class="title-info">
          <h2 class="pdf-title">{{ latest.title.replace('.pdf', '') }}</h2>
          <p class="pdf-meta">By 
            <span class="pdf-creator">{{ latest.creator }}</span>
             on {{ latest.created_date }} {{ latest.created_time }}</p>
        </div>
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
       <!-- Edit button -->
       <button class="btn btn-edit" @click="openEditDialog"> Edit</button>
         <!-- Delete Button with Confirmation -->
        <el-popconfirm
          confirm-button-text="Yes"
          cancel-button-text="No"
          :icon="InfoFilled"
          icon-color="#f44336"
          title="Are you sure you want to delete this PDF?"
          @confirm="deletePdf(latest.id)"
        >
          <template #reference>
            <el-button class="btn btn-delete">Delete</el-button>
          </template>
        </el-popconfirm>
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
      <div class="gallery">
        <div
          v-for="edition in editions"
          :key="edition.id"
          class="gallery-item"
          @click="view(edition)"
        >
            <img
              v-if="edition.thumbnail_path"
              :src="`/storage/${edition.thumbnail_path}`"
              alt="PDF Thumbnail"
            />
        </div>
      </div>
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



  <!-- Edit Dialog -->
  <div v-if="showEditDialog" class="modal-overlay">
    <div class="modal slide-in">
      <!-- Header -->
      <div class="modal-header">
        <h2>Edit SenTalk</h2>
        <span class="close-btn" @click="closeEditDialog">&times;</span>
      </div>

      <!-- Form -->
      <div class="modal-body">
        <label>Title</label>
        <input v-model="editForm.title" type="text" />

        <label>Creator</label>
        <input v-model="editForm.creator" type="text" />

        <label>Created Date</label>
        <input v-model="editForm.created_date" type="text" />

        <label>Created Time</label>
        <input v-model="editForm.created_time" type="text" />
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button class="btn-confirm" @click="updatePdf">Confirm</button>
      </div>
    </div>
  </div>





  <!-- Not Found Dialog -->
  <div v-if="showNotFoundDialog" class="modal-overlay_">
    <div class="modal_">
      <div class="modal-header_">
        <h3>No PDF Found</h3>
      </div>
      <div class="modal-body_">
        <p>No PDF matches your search query.</p>
      </div>
      <div class="modal-footer_">
        <button class="btn-confirm" @click="closeNotFoundDialog">OK</button>
      </div>
    </div>
  </div>


</template>

<script>
import axios from "axios";
import { InfoFilled } from "@element-plus/icons-vue";





export default {
  data() {
    return {
      latest: null,
      editions: [],
      searchQuery: "",
      showEditDialog: false,
      showNotFoundDialog: false,
      editForm: {
      title: "",
      creator: "",
      created_date: "",
      created_time: "",
      },
    };
  },

  async mounted() {
    await this.fetchData();
  },

  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },


    closeNotFoundDialog() {
      this.showNotFoundDialog = false;
      this.clearSearch(); // reload original list after closing
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
      alert("Upload failed: " + (res.data.message || "Unknown error"));
    }
      } catch (err) {
        console.error("Upload failed:", err);
          alert("Upload failed. Check logs.");
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
          this.showNotFoundDialog = true;
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


    async deletePdf(id) {
      if (!id) return;
      try {
        const res = await axios.delete(`/sentalk/delete/${id}`);
        if (res.data.success) {
          this.latest = res.data.latest;
          this.editions = res.data.editions;
          window.location.reload();
        }
      } catch (err) {
        console.error("Delete failed:", err);
      }
    },



    clearSearch() {
      this.searchQuery = "";
      this.fetchData(); // reload original list
    },

    view(edition) {
      this.latest = edition;
    },


    // Open dialog with current values prefilled
    openEditDialog() {
        if (!this.latest) return;
        this.editForm = {
          title: this.latest.title || "",
          creator: this.latest.creator || "",
          created_date: this.latest.created_date || "",
          created_time: this.latest.created_time || "",
        };
        this.showEditDialog = true;
      },

      closeEditDialog() {
        this.showEditDialog = false;
      },

      async updatePdf() {
        try {
          const res = await axios.post(`/sentalk/update/${this.latest.id}`, this.editForm);

          if (res.data.success) {
            await this.fetchData();
            this.showEditDialog = false;
          } else {
            alert("Update failed: " + (res.data.message || "Unknown error"));
          }
        } catch (err) {
          console.error("Update failed:", err);
          alert("Update failed. Check logs.");
        }
      },
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Exo+2&family=Montserrat&family=Poppins&display=swap');

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
  margin-top: 10px;
}


.stats {
  font-size: 12px;
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
  border-radius: 3px; /* keep rounded */
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
.btn-upload,
.btn-edit {
  background-color: #144f9f;
  color: #fff !important;
}

.btn-search:hover,
.btn-download:hover,
.btn-upload:hover,
.btn-edit:hover {
  background-color: #0f3c7a;
}
.btn-clear,
.btn-delete {
  background: #e74c3c;
  color: #fff !important;
}
.btn-clear:hover,
.btn-delete:hover {
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
  margin-top: 10px;
}

.title-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 10px 0 20px 0; /* increased bottom margin as before */
  gap: 10px; /* optional: adds space between flex children */
  flex-wrap: wrap; /* optional: allows wrapping on smaller screens */
}

.title-info {
  display: flex;
  align-items: center;
  gap: 12px; /* space between heading and paragraph */
}

.pdf-title {
  font-family: 'Poppins', sans-serif;
  font-size: 26px;
  font-weight: 600;
  margin: 0;
  color: #333;
}

.pdf-creator {
  font-family: 'Montserrat', sans-serif;
  font-weight: 500;
}

.pdf-meta {
  font-family: 'Exo 2', sans-serif;
  font-weight: 400;
  margin: 0;
  font-size: 12px;
  color: #777;
}

/* Gallery layout */
.gallery {
  display: flex;
  flex-wrap: wrap;   /* allows items to wrap to next line */
  gap: 8px;
  padding: 5px 0;
}

.gallery-item {
  flex: 0 0 auto;
  width: 100px;      /* smaller thumbnail width */
  cursor: pointer;
  text-align: center;
}

.gallery-item img {
  width: 100%;
  height: 100px;
  object-fit: cover;
  object-position: top center; 
  border: 1px solid #ccc;
  border-radius: 6px;

}

.actions {
  display: flex;
  gap: 10px;  /* adds space between Upload + Edit */
  margin-top: 10px;
}

/* Modal Overlay */

/* Overlay */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  z-index: 5000;
}

.modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #fff;
  width: 400px;
  max-width: 90%;
  max-height: 60vh;
  padding: 20px;
  box-shadow: 0 0 12px rgba(0, 0, 0, 0.2);
  border-radius: 8px;
  z-index: 5100;
  overflow-y: auto;
  display: block; /* Optional, divs are block by default */
}


/* Slide animation */
@keyframes slideIn {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(0);
  }
}

/* Header */
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.modal-header h2 {
  margin: 0;
  font-size: 22px;
  font-weight: bold;
  color: #144f9f;
}

.close-btn {
  font-size: 24px;
  cursor: pointer;
  color: #333;
}

/* Body */
.modal-body label {
  display: block;
  margin: 12px 0 5px;
  font-weight: bold;
  font-size: 14px;
  color: #333;
}

.modal-body input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
  margin-bottom: 10px;
  font-size: 14px;
}

/* Footer */
.modal-footer {
  margin-top: auto; /* push button to bottom */
  text-align: center;
}

.btn-confirm {
  background: #144f9f;
  color: #fff;
  border: none;
  padding: 12px 20px;
  border-radius: 6px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  width: 100%;
}

.btn-confirm:hover {
  background: #0f3c7a;
}


/* not found dialog  */

/* Overlay */
.modal-overlay_ {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 5000;
}

/* Center Modal */
.modal_ {
  background: #fff;
  padding: 20px;
  width: 350px;
  border-radius: 8px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.modal-header_ h3 {
  margin-bottom: 15px;
  font-size: 20px;
  font-weight: bold;
  color: #144f9f;
}

.modal-body_ {
  margin-bottom: 20px;
  font-size: 14px;
  color: #333;
}

.modal-footer_ {
  text-align: center;
}

.btn-confirm {
  background: #144f9f;
  color: #fff;
  border: none;
  padding: 10px 18px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: bold;
  cursor: pointer;
}

.btn-confirm:hover {
  background: #0f3c7a;
}

/* provide feedback styling */
.provide-feedback span{
  margin: 0;
  font-size: 22px;
  font-weight: bold;
  color: #144f9f;
}

</style><template> 
  <div class="sentalk-card">

    <!-- Header Row with Search + Download -->
    <div class="top-bar">
      <!-- Search -->
      <div class="search-container">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search by title"
          class="search-input"
          @keyup.enter="searchPdf"
        />
        <button class="btn btn-search" @click="searchPdf">Search</button>
        <button v-if="searchQuery" class="btn btn-clear" @click="clearSearch">
          Clear
        </button>
      </div>

      <!-- provide feedback -->
       <div class="provide-feedback">
          <span>Provide Feedback</span>
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
        <div class="title-info">
          <h2 class="pdf-title">{{ latest.title.replace('.pdf', '') }}</h2>
          <p class="pdf-meta">By 
            <span class="pdf-creator">{{ latest.creator }}</span>
             on {{ latest.created_date }} {{ latest.created_time }}</p>
        </div>
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
       <!-- Edit button -->
       <button class="btn btn-edit" @click="openEditDialog"> Edit</button>
         <!-- Delete Button with Confirmation -->
        <el-popconfirm
          confirm-button-text="Yes"
          cancel-button-text="No"
          :icon="InfoFilled"
          icon-color="#f44336"
          title="Are you sure you want to delete this PDF?"
          @confirm="deletePdf(latest.id)"
        >
          <template #reference>
            <el-button class="btn btn-delete">Delete</el-button>
          </template>
        </el-popconfirm>
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
      <div class="gallery">
        <div
          v-for="edition in editions"
          :key="edition.id"
          class="gallery-item"
          @click="view(edition)"
        >
            <img
              v-if="edition.thumbnail_path"
              :src="`/storage/${edition.thumbnail_path}`"
              alt="PDF Thumbnail"
            />
        </div>
      </div>
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



  <!-- Edit Dialog -->
  <div v-if="showEditDialog" class="modal-overlay">
    <div class="modal slide-in">
      <!-- Header -->
      <div class="modal-header">
        <h2>Edit SenTalk</h2>
        <span class="close-btn" @click="closeEditDialog">&times;</span>
      </div>

      <!-- Form -->
      <div class="modal-body">
        <label>Title</label>
        <input v-model="editForm.title" type="text" />

        <label>Creator</label>
        <input v-model="editForm.creator" type="text" />

        <label>Created Date</label>
        <input v-model="editForm.created_date" type="text" />

        <label>Created Time</label>
        <input v-model="editForm.created_time" type="text" />
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button class="btn-confirm" @click="updatePdf">Confirm</button>
      </div>
    </div>
  </div>





  <!-- Not Found Dialog -->
  <div v-if="showNotFoundDialog" class="modal-overlay_">
    <div class="modal_">
      <div class="modal-header_">
        <h3>No PDF Found</h3>
      </div>
      <div class="modal-body_">
        <p>No PDF matches your search query.</p>
      </div>
      <div class="modal-footer_">
        <button class="btn-confirm" @click="closeNotFoundDialog">OK</button>
      </div>
    </div>
  </div>


</template>

<script>
import axios from "axios";
import { InfoFilled } from "@element-plus/icons-vue";





export default {
  data() {
    return {
      latest: null,
      editions: [],
      searchQuery: "",
      showEditDialog: false,
      showNotFoundDialog: false,
      editForm: {
      title: "",
      creator: "",
      created_date: "",
      created_time: "",
      },
    };
  },

  async mounted() {
    await this.fetchData();
  },

  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },


    closeNotFoundDialog() {
      this.showNotFoundDialog = false;
      this.clearSearch(); // reload original list after closing
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
      alert("Upload failed: " + (res.data.message || "Unknown error"));
    }
      } catch (err) {
        console.error("Upload failed:", err);
          alert("Upload failed. Check logs.");
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
          this.showNotFoundDialog = true;
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


    async deletePdf(id) {
      if (!id) return;
      try {
        const res = await axios.delete(`/sentalk/delete/${id}`);
        if (res.data.success) {
          this.latest = res.data.latest;
          this.editions = res.data.editions;
          window.location.reload();
        }
      } catch (err) {
        console.error("Delete failed:", err);
      }
    },



    clearSearch() {
      this.searchQuery = "";
      this.fetchData(); // reload original list
    },

    view(edition) {
      this.latest = edition;
    },


    // Open dialog with current values prefilled
    openEditDialog() {
        if (!this.latest) return;
        this.editForm = {
          title: this.latest.title || "",
          creator: this.latest.creator || "",
          created_date: this.latest.created_date || "",
          created_time: this.latest.created_time || "",
        };
        this.showEditDialog = true;
      },

      closeEditDialog() {
        this.showEditDialog = false;
      },

      async updatePdf() {
        try {
          const res = await axios.post(`/sentalk/update/${this.latest.id}`, this.editForm);

          if (res.data.success) {
            await this.fetchData();
            this.showEditDialog = false;
          } else {
            alert("Update failed: " + (res.data.message || "Unknown error"));
          }
        } catch (err) {
          console.error("Update failed:", err);
          alert("Update failed. Check logs.");
        }
      },
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Exo+2&family=Montserrat&family=Poppins&display=swap');

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
  margin-top: 10px;
}


.stats {
  font-size: 12px;
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
  border-radius: 3px; /* keep rounded */
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
.btn-upload,
.btn-edit {
  background-color: #144f9f;
  color: #fff !important;
}

.btn-search:hover,
.btn-download:hover,
.btn-upload:hover,
.btn-edit:hover {
  background-color: #0f3c7a;
}
.btn-clear,
.btn-delete {
  background: #e74c3c;
  color: #fff !important;
}
.btn-clear:hover,
.btn-delete:hover {
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
  margin-top: 10px;
}

.title-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 10px 0 20px 0; /* increased bottom margin as before */
  gap: 10px; /* optional: adds space between flex children */
  flex-wrap: wrap; /* optional: allows wrapping on smaller screens */
}

.title-info {
  display: flex;
  align-items: center;
  gap: 12px; /* space between heading and paragraph */
}

.pdf-title {
  font-family: 'Poppins', sans-serif;
  font-size: 26px;
  font-weight: 600;
  margin: 0;
  color: #333;
}

.pdf-creator {
  font-family: 'Montserrat', sans-serif;
  font-weight: 500;
}

.pdf-meta {
  font-family: 'Exo 2', sans-serif;
  font-weight: 400;
  margin: 0;
  font-size: 12px;
  color: #777;
}

/* Gallery layout */
.gallery {
  display: flex;
  flex-wrap: wrap;   /* allows items to wrap to next line */
  gap: 8px;
  padding: 5px 0;
}

.gallery-item {
  flex: 0 0 auto;
  width: 100px;      /* smaller thumbnail width */
  cursor: pointer;
  text-align: center;
}

.gallery-item img {
  width: 100%;
  height: 100px;
  object-fit: cover;
  object-position: top center; 
  border: 1px solid #ccc;
  border-radius: 6px;

}

.actions {
  display: flex;
  gap: 10px;  /* adds space between Upload + Edit */
  margin-top: 10px;
}

/* Modal Overlay */

/* Overlay */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  z-index: 5000;
}

.modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #fff;
  width: 400px;
  max-width: 90%;
  max-height: 60vh;
  padding: 20px;
  box-shadow: 0 0 12px rgba(0, 0, 0, 0.2);
  border-radius: 8px;
  z-index: 5100;
  overflow-y: auto;
  display: block; /* Optional, divs are block by default */
}


/* Slide animation */
@keyframes slideIn {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(0);
  }
}

/* Header */
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.modal-header h2 {
  margin: 0;
  font-size: 22px;
  font-weight: bold;
  color: #144f9f;
}

.close-btn {
  font-size: 24px;
  cursor: pointer;
  color: #333;
}

/* Body */
.modal-body label {
  display: block;
  margin: 12px 0 5px;
  font-weight: bold;
  font-size: 14px;
  color: #333;
}

.modal-body input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
  margin-bottom: 10px;
  font-size: 14px;
}

/* Footer */
.modal-footer {
  margin-top: auto; /* push button to bottom */
  text-align: center;
}

.btn-confirm {
  background: #144f9f;
  color: #fff;
  border: none;
  padding: 12px 20px;
  border-radius: 6px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  width: 100%;
}

.btn-confirm:hover {
  background: #0f3c7a;
}


/* not found dialog  */

/* Overlay */
.modal-overlay_ {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 5000;
}

/* Center Modal */
.modal_ {
  background: #fff;
  padding: 20px;
  width: 350px;
  border-radius: 8px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.modal-header_ h3 {
  margin-bottom: 15px;
  font-size: 20px;
  font-weight: bold;
  color: #144f9f;
}

.modal-body_ {
  margin-bottom: 20px;
  font-size: 14px;
  color: #333;
}

.modal-footer_ {
  text-align: center;
}

.btn-confirm {
  background: #144f9f;
  color: #fff;
  border: none;
  padding: 10px 18px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: bold;
  cursor: pointer;
}

.btn-confirm:hover {
  background: #0f3c7a;
}

/* provide feedback styling */
.provide-feedback {
  cursor: pointer;
  display: inline-block;
  padding: 6px 12px;
  border-radius: 6px;
  transition: all 0.3s ease;
}

.provide-feedback span {
  font-size: 16px;
  font-weight: bold;
  color: #144f9f; /* same theme blue */
  font-family: 'Poppins', sans-serif; /* same as Edit SenTalk heading */
}

.provide-feedback:hover {
  background-color: #144f9f; /* theme blue */
}

.provide-feedback:hover span {
  color: #fff; /* invert text color */
}


</style>
