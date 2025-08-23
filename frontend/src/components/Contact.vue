<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" md="8" lg="6">
        <v-card>
          <v-card-title>
            <h2 class="text-h4 font-weight-bold text-center w-100">Kontakt</h2>
          </v-card-title>
          <v-card-text>
            <v-form ref="contactForm" v-model="valid" lazy-validation>
              <v-text-field
                v-model="form.name"
                :rules="nameRules"
                label="Imię i nazwisko"
                required
                variant="outlined"
                class="mb-3"
              ></v-text-field>
              
              <v-text-field
                v-model="form.email"
                :rules="emailRules"
                label="Email"
                required
                variant="outlined"
                class="mb-3"
              ></v-text-field>
              
              <v-textarea
                v-model="form.message"
                :rules="messageRules"
                label="Wiadomość"
                required
                variant="outlined"
                rows="5"
                class="mb-3"
              ></v-textarea>
              
              <v-btn
                :disabled="!valid || loading"
                :loading="loading"
                color="primary"
                size="large"
                block
                @click="submitForm"
              >
                Wyślij wiadomość
              </v-btn>
            </v-form>
          </v-card-text>
        </v-card>
        
        <v-alert
          v-if="alert.show"
          :type="alert.type"
          class="mt-4"
          dismissible
          @input="alert.show = false"
        >
          {{ alert.message }}
        </v-alert>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'

// Form state
const valid = ref(false)
const loading = ref(false)
const contactForm = ref()

// Form data
const form = reactive({
  name: '',
  email: '',
  message: ''
})

// Alert state
const alert = reactive({
  show: false,
  type: 'success' as 'success' | 'error' | 'warning' | 'info',
  message: ''
})

// Validation rules
const nameRules = [
  (v: string) => !!v || 'Imię i nazwisko jest wymagane',
  (v: string) => (v && v.length >= 2) || 'Imię i nazwisko musi mieć co najmniej 2 znaki'
]

const emailRules = [
  (v: string) => !!v || 'Email jest wymagany',
  (v: string) => /.+@.+\..+/.test(v) || 'Email musi być poprawny'
]

const messageRules = [
  (v: string) => !!v || 'Wiadomość jest wymagana',
  (v: string) => (v && v.length >= 10) || 'Wiadomość musi mieć co najmniej 10 znaków'
]

// Show alert function
const showAlert = (type: 'success' | 'error' | 'warning' | 'info', message: string) => {
  alert.type = type
  alert.message = message
  alert.show = true
}

// Submit form function
const submitForm = async () => {
  const { valid: isValid } = await contactForm.value.validate()
  
  if (!isValid) {
    showAlert('error', 'Proszę poprawić błędy w formularzu')
    return
  }
  
  loading.value = true
  
  try {
    const response = await fetch('/api/contact', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(form)
    })
    
    if (response.ok) {
      showAlert('success', 'Wiadomość została wysłana pomyślnie!')
      // Reset form
      form.name = ''
      form.email = ''
      form.message = ''
      contactForm.value.resetValidation()
    } else {
      const errorData = await response.json()
      showAlert('error', errorData.message || 'Wystąpił błąd podczas wysyłania wiadomości')
    }
  } catch (error) {
    console.error('Error submitting form:', error)
    showAlert('error', 'Wystąpił błąd podczas wysyłania wiadomości')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.v-card {
  border-radius: 12px;
}

.v-btn {
  text-transform: none;
  font-weight: 600;
}
</style>