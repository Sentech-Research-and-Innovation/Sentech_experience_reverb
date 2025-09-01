<template>
  <div class="sentalk-container">
    <div class="sentalk-header">
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search by name" v-model="searchQuery">
      </div>
      <div class="action-buttons">
        <button class="btn btn-upload" @click="openUploadModal">
          <i class="fas fa-upload"></i> Upload
        </button>
        <button class="btn btn-download" @click="downloadCurrentPdf">
          <i class="fas fa-download"></i> Download
        </button>
      </div>
    </div>

    <div class="edition-card" v-if="currentEdition">
      <div class="edition-title">{{ currentEdition.title }}</div>
      <div class="edition-meta">
        <span class="creator">By {{ currentEdition.creator }}</span>
        <span class="date">on {{ formatDate(currentEdition.created_at) }}</span>
        <span class="stats">
          - {{ currentEdition.views }} views - {{ currentEdition.downloads }} downloads - {{ currentEdition.likes }} likes
        </span>
        <a href="#" class="view-new-tab" @click.prevent="openInNewTab">view on a new tab</a>
      </div>

      <div class="pdf-preview">
        <div class="pdf-header">
          <h2>{{ currentEdition.title.toUpperCase() }}</h2>
          <h3>SENTALK</h3>
        </div>
        <div class="pdf-content">
          <iframe 
            v-if="currentEdition.pdf_url"
            :src="currentEdition.pdf_url"
            width="100%"
            height="100%"
            frameborder="0"
          ></iframe>
          <div v-else class="pdf-placeholder">
            <i class="fas fa-file-pdf"></i>
            <p>PDF Preview</p>
          </div>
        </div>
      </div>
    </div>

    <div class="pagination">
      <button class="pagination-btn" @click="prevPage" :disabled="currentPage === 1">Previous</button>
      <button 
        v-for="page in visiblePages" 
        :key="page" 
        class="pagination-btn" 
        :class="{ active: page === currentPage }"
        @click="goToPage(page)"
      >
        {{ page }}
      </button>
      <span class="pagination-ellipsis" v-if="showEllipsis">...</span>
      <button class="pagination-btn" @click="nextPage" :disabled="currentPage === totalPages">Next</button>
    </div>

    <!-- Upload Modal -->
    <div v-if="showUploadModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Upload New Edition</h3>
        <div class="upload-form">
          <div class="form-group">
            <label>Title</label>
            <input type="text" v-model="newEdition.title" placeholder="Edition Title">
          </div>
          <div class="form-group">
            <label>Creator</label>
            <input type="text" v-model="newEdition.creator" placeholder="Creator Name">
          </div>
          <div class="form-group">
            <label>PDF File</label>
            <input type="file" @change="handleFileUpload" accept=".pdf">
          </div>
          <div class="form-actions">
            <button class="btn btn-cancel" @click="closeUploadModal">Cancel</button>
            <button class="btn btn-primary" @click="uploadEdition" :disabled="!canUpload">Upload</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SenTalkPage',
  data() {
    return {
      searchQuery: '',
      currentPage: 1,
      totalPages: 20,
      currentEdition: {
        title: 'SenTalk August Edition 2025',
        creator: 'Machabal',
        created_at: '2025-08-18T07:14:00',
        views: 109,
        downloads: 23,
        likes: 3,
        pdf_url: null
      },
      showUploadModal: false,
      newEdition: {
        title: '',
        creator: '',
        file: null
      }
    }
  },
  computed: {
    visiblePages() {
      const pages = [];
      const start = Math.max(1, this.currentPage - 5);
      const end = Math.min(this.totalPages, start + 9);
      
      for (let i = start; i <= end; i++) {
        pages.push(i);
      }
      return pages;
    },
    showEllipsis() {
      return this.totalPages > 10 && this.currentPage < this.totalPages - 5;
    },
    canUpload() {
      return this.newEdition.title && this.newEdition.creator && this.newEdition.file;
    }
  },
  methods: {
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    openUploadModal() {
      this.showUploadModal = true;
    },
    closeUploadModal() {
      this.showUploadModal = false;
      this.newEdition = {
        title: '',
        creator: '',
        file: null
      };
    },
    handleFileUpload(event) {
      this.newEdition.file = event.target.files[0];
    },
    uploadEdition() {
      // Here you would implement the actual upload logic
      console.log('Uploading new edition:', this.newEdition);
      // After successful upload, you might want to:
      // 1. Close the modal
      // 2. Refresh the editions list
      // 3. Show a success message
      this.closeUploadModal();
    },
    downloadCurrentPdf() {
      // Implement download logic
      console.log('Downloading current PDF');
    },
    openInNewTab() {
      window.open(this.currentEdition.pdf_url, '_blank');
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.loadEdition();
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.loadEdition();
      }
    },
    goToPage(page) {
      this.currentPage = page;
      this.loadEdition();
    },
    loadEdition() {
      // Here you would fetch the edition data for the current page
      // This is a mock implementation
      console.log('Loading edition for page:', this.currentPage);
    }
  },
  mounted() {
    // Load initial data
    this.loadEdition();
  }
}
</script>

<style scoped>
.sentalk-container {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  max-width: 1000px;
  margin: 20px auto;
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.sentalk-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 15px;
}

.search-box {
  display: flex;
  align-items: center;
  background: #f5f7f9;
  border-radius: 4px;
  padding: 8px 12px;
  flex: 1;
  max-width: 300px;
}

.search-box i {
  color: #7a7a7a;
  margin-right: 8px;
}

.search-box input {
  border: none;
  background: transparent;
  outline: none;
  width: 100%;
  font-size: 14px;
}

.action-buttons {
  display: flex;
  gap: 10px;
}

.btn {
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s;
}

.btn-upload {
  background: #4CAF50;
  color: white;
}

.btn-upload:hover {
  background: #3d8b40;
}

.btn-download {
  background: #2196F3;
  color: white;
}

.btn-download:hover {
  background: #0b7dda;
}

.btn-primary {
  background: #2196F3;
  color: white;
}

.btn-cancel {
  background: #f5f5f5;
  color: #333;
}

.edition-card {
  background: #f9f9f9;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
}

.edition-title {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 10px;
  color: #2c3e50;
}

.edition-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  font-size: 14px;
  color: #7a7a7a;
  margin-bottom: 20px;
  align-items: center;
}

.view-new-tab {
  color: #2196F3;
  text-decoration: none;
}

.view-new-tab:hover {
  text-decoration: underline;
}

.pdf-preview {
  background: white;
  border-radius: 6px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.pdf-header {
  background: #2c3e50;
  color: white;
  padding: 15px;
  text-align: center;
}

.pdf-header h2 {
  font-size: 18px;
  margin: 0 0 5px 0;
  font-weight: 600;
}

.pdf-header h3 {
  font-size: 14px;
  margin: 0;
  font-weight: 400;
  letter-spacing: 1px;
}

.pdf-content {
  height: 400px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f0f2f5;
}

.pdf-placeholder {
  text-align: center;
  color: #7a7a7a;
}

.pdf-placeholder i {
  font-size: 48px;
  color: #e74c3c;
  margin-bottom: 10px;
}

.pdf-placeholder p {
  margin: 0;
  font-size: 14px;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  gap: 6px;
  margin-top: 20px;
}

.pagination-btn {
  padding: 6px 12px;
  border: 1px solid #ddd;
  background: white;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: all 0.2s;
}

.pagination-btn:hover:not(:disabled) {
  background: #f5f5f5;
}

.pagination-btn.active {
  background: #2196F3;
  color: white;
  border-color: #2196F3;
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-ellipsis {
  padding: 6px 4px;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 90%;
  max-width: 500px;
}

.upload-form {
  margin-top: 15px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 500;
}

.form-group input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
}

@media (max-width: 768px) {
  .sentalk-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .search-box {
    max-width: 100%;
    width: 100%;
  }
  
  .action-buttons {
    width: 100%;
    justify-content: space-between;
  }
  
  .btn {
    flex: 1;
    justify-content: center;
  }
  
  .edition-meta {
    flex-direction: column;
    align-items: flex-start;
    gap: 5px;
  }
}
</style>
