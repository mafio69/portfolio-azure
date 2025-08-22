<!-- src/views/About.vue -->
<template>
  <v-container>
    <h1 class="text-h4 my-4 fancy-title">O mnie</h1>
    <v-card class="animated-card">
      <v-card-text>
        <p class="text-body-1 mb-4">
          Witaj na mojej stronie! Jestem doświadczonym programistą PHP z pasją do tworzenia czystego, wydajnego i skalowalnego oprogramowania. Od lat rozwijam swoje umiejętności w ekosystemie PHP, ale chętnie sięgam również po nowoczesne technologie frontendowe, takie jak Vue.js, aby budować kompletne i interaktywne aplikacje.
        </p>
        <p class="text-body-1 mb-4">
          Kieruję się zasadami SOLID i Czystego Kodu, ponieważ wierzę, że dobrze zorganizowany i zrozumiały kod jest kluczem do sukcesu każdego projektu. Moje doświadczenie obejmuje pracę z różnorodnymi frameworkami i narzędziami, a także konteneryzacją aplikacji przy użyciu Dockera. Zawsze staram się podchodzić do problemów analitycznie, szukając optymalnych i długoterminowych rozwiązań.
        </p>
        <h2 class="text-h5 mt-6 mb-4">Moje Kluczowe Umiejętności</h2>
        <div class="d-flex flex-wrap ga-2">
          <!-- PHP & Frameworks -->
          <v-chip class="skill-chip" color="indigo" variant="elevated">PHP</v-chip>
          <v-chip class="skill-chip" color="indigo" variant="elevated">Symfony</v-chip>
          <v-chip class="skill-chip" color="indigo" variant="elevated">Slim</v-chip>
          <v-chip class="skill-chip" color="indigo" variant="elevated">Laravel</v-chip>

          <!-- JS & Frameworks -->
          <v-chip class="skill-chip" color="yellow-darken-3" variant="elevated">JavaScript</v-chip>
          <v-chip class="skill-chip" color="yellow-darken-3" variant="elevated">Vue.js</v-chip>
          <v-chip class="skill-chip" color="yellow-darken-3" variant="elevated">Vuetify</v-chip>

          <!-- DevOps & Databases -->
          <v-chip class="skill-chip" color="blue" variant="elevated">Docker</v-chip>
          <v-chip class="skill-chip" color="orange" variant="elevated">MySQL</v-chip>

          <!-- Principles & Tools -->
          <v-chip class="skill-chip" color="deep-orange" variant="elevated">SOLID</v-chip>
          <v-chip class="skill-chip" color="deep-orange" variant="elevated">Clean Code</v-chip>
          <v-chip class="skill-chip" color="deep-orange" variant="elevated">Testy jednostkowe</v-chip>
          <v-chip class="skill-chip" color="deep-orange" variant="elevated">Git</v-chip>
          <v-chip class="skill-chip" color="deep-orange" variant="elevated">Jira</v-chip>
        </div>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
// W przyszłości można tu dodać logikę, np. pobieranie danych o mnie z API
</script>

<style scoped>
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

.fancy-title {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  color: #3f51b5; /* Vuetify primary color */
  text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
  overflow: hidden; /* Hide the text initially */
  white-space: nowrap; /* Keep text on one line */
  animation: reveal-text 1.5s forwards; /* Apply animation */
}

@keyframes reveal-text {
  from {
    width: 0;
  }
  to {
    width: 100%;
  }
}

.animated-card {
  opacity: 0;
  animation: fade-in-up 0.5s ease-out 0.2s forwards; /* Opóźnienie 0.2s */
}

.skill-chip {
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.skill-chip:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>
