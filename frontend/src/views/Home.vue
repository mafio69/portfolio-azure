<template>
  <v-container fluid class="home-hero d-flex align-center justify-center text-center">
    <v-row justify="center">
      <v-col cols="12" md="10">
        <!-- Placeholder na zdjęcie profilowe -->
        <v-avatar size="150" class="mb-6 animated-avatar">
          <v-img src="https://via.placeholder.com/150" alt="Mariusz Franciszczak"></v-img>
        </v-avatar>

        <h1 class="display-2 font-weight-bold mb-4 animated-hero-title">
          Mariusz Franciszczak
        </h1>
        <h2 class="text-h4 font-weight-light mb-6 animated-hero-subtitle">
          Twój Doświadczony Programista PHP & Web Developer
        </h2>
        <p class="text-h6 mb-8 animated-hero-description">
          Z pasją tworzę innowacyjne i efektywne rozwiązania webowe, łącząc solidne podstawy PHP z nowoczesnymi technologiami frontendowymi.
        </p>
        <v-btn
          color="primary"
          size="x-large"
          class="animated-hero-button mr-4"
          to="/projects"
        >
          <v-icon left>mdi-folder-multiple-image</v-icon>
          Zobacz Moje Projekty
        </v-btn>
        <v-btn
          color="secondary"
          size="x-large"
          class="animated-hero-button"
          to="/contact"
        >
          <v-icon left>mdi-email</v-icon>
          Skontaktuj Się
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
// Brak specyficznej logiki dla tej strony
</script>

<style scoped>
.home-hero {
  min-height: calc(100vh - 64px - 64px); /* Wysokość ekranu minus app-bar i footer */
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;

  /* Bardziej złożone tło z animacją */
  background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
  background-size: 400% 400%;
  animation: gradient-animation 15s ease infinite;
}

@keyframes gradient-animation {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

/* Animacje dla elementów hero section */
.animated-avatar {
  opacity: 0;
  transform: translateY(-20px);
  animation: fade-in-down 1s ease-out forwards;
}

.animated-hero-title {
  opacity: 0;
  animation: fade-in-up 1s ease-out 0.2s forwards;
}

.animated-hero-subtitle {
  opacity: 0;
  animation: fade-in-up 1s ease-out 0.4s forwards;
}

.animated-hero-description {
  opacity: 0;
  animation: fade-in-up 1s ease-out 0.6s forwards;
}

.animated-hero-button {
  opacity: 0;
  animation: fade-in-up 1s ease-out 0.8s forwards;
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in-down {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>