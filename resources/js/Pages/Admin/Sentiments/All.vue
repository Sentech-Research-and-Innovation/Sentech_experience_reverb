<template>
  <div>
    <!-- Main content -->
    <div class="content-wrapper">
      <!-- Your existing content goes here -->
    </div>

    <!-- Persistent banner -->
    <div v-if="showBanner" class="custom-banner">
      <div class="custom-banner-content">
        <h3 class="custom-banner-heading">Sentiment analysis is currently under construction</h3>
        <p class="custom-banner-subtext">
          The R&amp;I team is currently working on Sentiment analysis
        </p>
        <button class="custom-banner-button" @click="closeBanner">Continue</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showBanner: true,
    };
  },
  methods: {
    closeBanner() {
      this.showBanner = false;
    },
  },
};
</script>

<style scoped>
.content-wrapper {
  padding-bottom: 120px; /* So main content doesn't get hidden behind the fixed banner */
}

.custom-banner {
  position: fixed;
  bottom: 0;
  left: 250px; /* Adjust this if your sidebar is wider or narrower */
  right: 0;
  background-color: #0E4C9D; /* Blue from the provided image */
  color: white;
  z-index: 9999;
  padding: 20px;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.15);
  display: flex;
  justify-content: center;
  align-items: center;
}

.custom-banner-content {
  max-width: 900px;
  text-align: center;
}

.custom-banner-heading {
  font-size: 20px;
  font-weight: bold;
  margin-bottom: 8px;
}

.custom-banner-subtext {
  font-size: 16px;
  margin-bottom: 16px;
}

.custom-banner-button {
  background-color: white;
  color: #0E4C9D;
  border: 1px solid #0E4C9D;
  padding: 10px 20px;
  font-weight: bold;
  border-radius: 4px;
  cursor: pointer;
}

.custom-banner-button:hover {
  background-color: #e6e6e6;
}
</style>
