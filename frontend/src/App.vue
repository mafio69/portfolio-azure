<template>
  <v-app>
    <v-app-bar 
      app 
      color="primary" 
      dark 
      elevation="4"
      height="64"
    >
      <v-toolbar-title class="app-bar-title">
        <v-icon left class="mr-2">mdi-account-circle</v-icon>
        Mariusz Franciszczak - Web Developer
      </v-toolbar-title>
      
      <v-spacer></v-spacer>
      
      <v-btn text to="/" class="mx-2">
        <v-icon left>mdi-home</v-icon>
        Home
      </v-btn>
      
      <v-btn text to="/projects" class="mx-2">
        <v-icon left>mdi-folder-multiple-image</v-icon>
        Projekty
      </v-btn>
      
      <v-btn text to="/about"  class="mx-2">
        <v-icon left>mdi-account</v-icon>
        O Mnie
      </v-btn>
      
      <v-btn text to="/contact" class="mx-2">
        <v-icon left>mdi-email</v-icon>
        Kontakt
      </v-btn>
    </v-app-bar>

    <v-main class="bg-background">
      <router-view v-slot="{ Component }">
        <v-fade-transition mode="out-in">
          <component :is="Component" />
        </v-fade-transition>
      </router-view>
    </v-main>

    <v-footer 
      app 
      color="surface" 
      class="text-center pa-4"
      elevation="8"
    >
      <div class="text-body-2 text-secondary">
        © {{ new Date().getFullYear() }} Mariusz Franciszczak. Wszystkie prawa zastrzeżone.
      </div>
    </v-footer>
  </v-app>
</template>

<script setup>
</script>

<style>
.app-bar-title {
  font-family: 'Poppins', sans-serif !important;
  font-weight: 700 !important;
  font-size: 1.6rem !important; /* Slightly larger */
  animation: fade-in 1s ease-out forwards;
}

@keyframes fade-in {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>

