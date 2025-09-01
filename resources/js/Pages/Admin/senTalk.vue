<template>
  <div class="sentalk-container">
    <div class="sentalk-header">
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search by name" v-model="searchQuery" @input="searchEditions">
      </div>
      <div class="action-buttons">
        <button class="btn btn-upload" @click="openUploadModal">
          <i class="fas fa-upload"></i> Upload
        </button>
        <button class="btn btn-download" @click="downloadCurrentPdf" :disabled="!currentEdition">
          <i class="fas fa-download"></i> Download
        </button>
      </div>
    </div>

    <div v-if="loading" class="loading">Loading...</div>

    <div v-else>
      <div class="edition-card" v-if="currentEdition">
        <div class="edition-title">{{ currentEdition.title }}</div>
        <div class="edition-meta">
          <span class="creator">By {{ currentEdition.creator }}</span>
          <span class="date">on {{ formatDate(currentEdition.created_at) }}</span>
          <span class="stats">
            - {{ currentEdition.number_views }} views - {{ currentEdition.number_downloads }} downloads - {{ currentEdition.number_likes }} likes
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

      <div v-if="editions.length === 0" class="no-editions">
        <i class="fas fa-file-pdf"></i>
        <p>No SenTalk editions found</p>
      </div>
    </div>

    <div class="pagination" v-if="editions.length > 0">
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
            <label>Title *</label>
            <input type="text" v-model="newEdition.title" placeholder="Edition Title" required>
          </div>
          <div class="form-group">
            <label>Creator *</label>
            <input type="text" v-model="newEdition.creator" placeholder="Creator Name" required>
          </div>
          <div class="form-group">
            <label>PDF File *</label>
            <input type="file" ref="fileInput" @change="handleFileUpload" accept=".pdf" required>
            <small v-if="newEdition.file">Selected: {{ newEdition.file.name }}</small>
          </div>
          <div class="upload-status" v-if="uploadStatus">
            {{ uploadStatus }}
          </div>
          <div class="form-actions">
            <button class="btn btn-cancel" @click="closeUploadModal">Cancel</button>
            <button class="btn btn-primary" @click="uploadEdition" :disabled="!canUpload || uploading">
              <span v-if="uploading">Uploading...</span>
              <span v-else>Upload</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'SenTalkPage',
  data() {
    return {
      searchQuery: '',
      currentPage: 1,
      totalPages: 1,
      editions: [],
      currentEdition: null,
      loading: true,
      showUploadModal: false,
      uploading: false,
      uploadStatus: '',
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
    
    async loadEditions() {
      this.loading = true;
      try {
        const response = await axios.get('/sentalk', {
          params: {
            page: this.currentPage,
            search: this.searchQuery
          }
        });
        
        this.editions = response.data.data;
        this.totalPages = response.data.last_page;
        
        if (this.editions.length > 0) {
          this.currentEdition = this.editions[0];
          // Add full URL for PDF preview
          if (this.currentEdition.pdf_path) {
            this.currentEdition.pdf_url = `/storage/${this.currentEdition.pdf_path}`;
          }
        } else {
          this.currentEdition = null;
        }
      } catch (error) {
        console.error('Error loading editions:', error);
        alert('Failed to load SenTalk editions');
      } finally {
        this.loading = false;
      }
    },
    
    async searchEditions() {
      // Debounce search to avoid too many requests
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => {
        this.currentPage = 1;
        this.loadEditions();
      }, 500);
    },
    
    openUploadModal() {
      this.showUploadModal = true;
    },
    
    closeUploadModal() {
      this.showUploadModal = false;
      this.uploadStatus = '';
      this.newEdition = {
        title: '',
        creator: '',
        file: null
      };
      
      // Reset file input
      if (this.$refs.fileInput) {
        this.$refs.fileInput.value = '';
      }
    },
    
    handleFileUpload(event) {
      this.newEdition.file = event.target.files[0];
    },
    
    async uploadEdition() {
      if (!this.canUpload) return;
      
      this.uploading = true;
      this.uploadStatus = 'Uploading...';
      
      try {
        const formData = new FormData();
        formData.append('title', this.newEdition.title);
        formData.append('creator', this.newEdition.creator);
        formData.append('pdf', this.newEdition.file);
        
        const response = await axios.post('/sentalk/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        
        this.uploadStatus = 'Upload successful!';
        
        // Close modal after a brief delay
        setTimeout(() => {
          this.closeUploadModal();
          this.loadEditions(); // Reload the editions list
        }, 1500);
        
      } catch (error) {
        console.error('Upload error:', error);
        this.uploadStatus = 'Upload failed. Please try again.';
        
        if (error.response && error.response.data.errors) {
          const errors = error.response.data.errors;
          this.uploadStatus = Object.values(errors).flat().join(', ');
        }
      } finally {
        this.uploading = false;
      }
    },
    
    async downloadCurrentPdf() {
      if (!this.currentEdition) return;
      
      try {
        // Increment download count
        await axios.get(`/sentalk/${this.currentEdition.id}/download`);
        
        // Create download link
        const link = document.createElement('a');
        link.href = `/storage/${this.currentEdition.pdf_path}`;
        link.download = this.currentEdition.title + '.pdf';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Reload to update download count
        this.loadEditions();
        
      } catch (error) {
        console.error('Download error:', error);
        alert('Failed to download PDF');
      }
    },
    
    openInNewTab() {
      if (this.currentEdition && this.currentEdition.pdf_url) {
        window.open(this.currentEdition.pdf_url, '_blank');
        
        // Increment view count
        axios.get(`/sentalk/${this.currentEdition.id}/view`)
          .then(() => this.loadEditions())
          .catch(err => console.error('Error incrementing view count:', err));
      }
    },
    
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.loadEditions();
      }
    },
    
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.loadEditions();
      }
    },
    
    goToPage(page) {
      this.currentPage = page;
      this.loadEditions();
    }
  },
  
  mounted() {
    this.loadEditions();
  }
}
</script>

<style scoped>
/* Your existing styles here, with a few additions */

.loading {
  text-align: center;
  padding: 40px;
  font-size: 18px;
}

.no-editions {
  text-align: center;
  padding: 40px;
  color: #7a7a7a;
}

.no-editions i {
  font-size: 48px;
  margin-bottom: 15px;
  color: #ddd;
}

.upload-status {
  margin: 15px 0;
  padding: 10px;
  border-radius: 4px;
  background: #f8f9fa;
  text-align: center;
}

.form-group small {
  display: block;
  margin-top: 5px;
  color: #666;
  font-style: italic;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
