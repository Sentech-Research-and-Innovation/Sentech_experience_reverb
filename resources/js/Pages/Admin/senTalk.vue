<template>
  <div class="sentalk">
    <h2>SenTalk</h2>

    <div v-if="latest">
      <h3>{{ latest.title }} - {{ latest.creator }}</h3>
      <p>{{ latest.created_at }}</p>
      <p>{{ latest.number_views }} views · {{ latest.number_downloads }} downloads · {{ latest.number_likes }} likes</p>

      <iframe
        v-if="latest.pdf_path"
        :src="`/storage/${latest.pdf_path}`"
        width="100%"
        height="500px"
      ></iframe>

      <div>
        <button @click="download(latest.id)">Download</button>
      </div>
    </div>

    <div>
      <h4>Upload new edition</h4>
      <input type="file" @change="onFileChange" />
      <input v-model="title" placeholder="Title" />
      <input v-model="creator" placeholder="Creator" />
      <button @click="upload">Upload</button>
    </div>

    <div>
      <h4>Older Editions</h4>
      <ul>
        <li v-for="edition in editions" :key="edition.id">
          <a @click="view(edition.id)" href="javascript:void(0)">
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
      latest: null,
      editions: [],
      file: null,
      title: "",
      creator: "",
    };
  },
  mounted() {
    this.fetchEditions();
  },
  methods: {
    fetchEditions() {
      axios.get("/admin/sentalk").then((res) => {
        this.editions = res.data.data;
        this.latest = this.editions[0];
      });
    },
    onFileChange(e) {
      this.file = e.target.files[0];
    },
    upload() {
      let formData = new FormData();
      formData.append("pdf", this.file);
      formData.append("title", this.title);
      formData.append("creator", this.creator);

      axios.post("/admin/sentalk/upload", formData).then(() => {
        this.fetchEditions();
      });
    },
    view(id) {
      axios.get(`/admin/sentalk/${id}`).then((res) => {
        this.latest = res.data;
      });
    },
    download(id) {
      window.open(`/admin/sentalk/download/${id}`, "_blank");
    },
  },
};
</script>
