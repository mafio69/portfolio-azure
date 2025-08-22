<template>
  <v-container>
    <h1 class="text-h4 my-4 fancy-title">Kontakt</h1>
    <v-row>
      <!-- Kolumna z informacjami kontaktowymi -->
      <v-col cols="12" md="5">
        <v-card class="pa-4 animated-card-left">
          <v-card-title>Informacje Kontaktowe</v-card-title>
          <v-card-text>
            <v-list>
              <v-list-item prepend-icon="mdi-email">
                <v-list-item-title>Email</v-list-item-title>
                <v-list-item-subtitle>mariusz.franciszczak@gmail.com</v-list-item-subtitle>
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item prepend-icon="mdi-map-marker">
                <v-list-item-title>Lokalizacja</v-list-item-title>
                <v-list-item-subtitle>Starogard Gdański, Polska</v-list-item-subtitle>
              </v-list-item>
            </v-list>
          </v-card-text>
        </v-card>
      </v-col>

      <!-- Kolumna z formularzem kontaktowym -->
      <v-col cols="12" md="7">
        <v-card class="pa-4 animated-card-right">
          <v-card-title>Napisz do mnie</v-card-title>
          <v-card-text>
            <v-form @submit.prevent="handleSubmit">
              <v-text-field
                v-model="form.name"
                label="Imię i nazwisko"
                variant="outlined"
                class="mb-2"
              ></v-text-field>
              <v-text-field
                v-model="form.email"
                label="Adres email"
                type="email"
                variant="outlined"
                class="mb-2"
              ></v-text-field>
              <v-textarea
                v-model="form.message"
                label="Wiadomość"
                variant="outlined"
                rows="5"
              ></v-textarea>
              <v-btn type="submit" color="primary" class="mt-4">Wyślij</v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const form = ref({
  name: '',
  email: '',
  message: '',
});

const handleSubmit = () => {
  // Logika wysyłania formularza (na razie tylko w konsoli)
  console.log('Dane formularza:', form.value);
  alert('Funkcjonalność wysyłania jest w budowie!');
};
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

.animated-card-left {
  opacity: 0;
  animation: fade-in-up 0.5s ease-out 0.2s forwards;
}

.animated-card-right {
  opacity: 0;
  animation: fade-in-up 0.5s ease-out 0.4s forwards;
}
</style>
