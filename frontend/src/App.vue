<template>
  <v-app>
    <v-app-bar 
      app 
      color="primary" 
      dark 
      elevation="4"
      height="64"
    >
      <v-toolbar-title class="text-h5 font-weight-bold">
        <v-icon left class="mr-2">mdi-account-circle</v-icon>
        Moje Portfolio
      </v-toolbar-title>
      
      <v-spacer></v-spacer>
      
      <v-btn text to="/" class="mx-2">
        <v-icon left>mdi-home</v-icon>
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
      <router-view></router-view>
    </v-main>

    <v-footer 
      app 
      color="surface" 
      class="text-center pa-4"
      elevation="8"
    >
      <div class="text-body-2 text-secondary">
        © {{ new Date().getFullYear() }} Moje Portfolio. Wszystkie prawa zastrzeżone.
      </div>
    </v-footer>
  </v-app>
</template>

<script setup>
</script>

