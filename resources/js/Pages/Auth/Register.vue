<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

defineOptions({ layout: AppLayout });

const form = useForm({
    name: '',
    first_name: '',
    last_name: '',
    phone: '',
    city: '',
    apartment: '',
    email: '',
    consent_personal_data: false,
    consent_email: false,
    consent_marketing: false,
    terms: false,
});

const feedback = ref(null);
const feedbackType = ref('success');
const showSuccessModal = ref(false);

const submit = async () => {
    form.name = `${form.first_name} ${form.last_name}`.trim();
    feedback.value = null;
    feedbackType.value = 'success';
    form.processing = true;
    try {
        const response = await fetch('/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify(form),
        });

        if (response.ok) {
            showSuccessModal.value = true;
        } else {
            const data = await response.json();
            feedback.value = 'Nie udało się zarejestrować. Popraw błędy w formularzu.';
            feedbackType.value = 'error';
            if (data.errors) {
                Object.keys(form.errors).forEach(key => delete form.errors[key]);
                Object.assign(form.errors, data.errors);
            }
        }
    } catch (e) {
        feedback.value = 'Wystąpił błąd sieci. Spróbuj ponownie.';
        feedbackType.value = 'error';
    } finally {
        form.processing = false;
    }
};
</script>

<template>
    <Head title="Register" />

    <ConfirmationModal :show="showSuccessModal" @close="showSuccessModal = false">
        <template #title>
            <div class="text-2xl  text-center text-white">Rejestracja się powiodła</div>
            <div class="text-2xl  text-center mt-2">Może właśnie teraz Cię obserwuję</div>
        </template>
        <template #content>
            <div class="text-center text-white italic mt-4">Na podany adres email zostanie wysłany login oraz hasło</div>
        </template>
    </ConfirmationModal>

    <div class="min-h-screen flex flex-col bg-black font-brandon-grotesque">
      <div class="flex-1 flex items-center justify-center pt-24 pb-12">
        <AuthenticationCard>
          <template #logo>
              <AuthenticationCardLogo />
          </template>

          <div v-if="feedback" :class="[feedbackType === 'success' ? 'bg-green-500' : 'bg-red-500', 'text-white p-4 mb-4 rounded text-center']">
              {{ feedback }}
          </div>

          <form @submit.prevent="submit" :class="['register-form bg-opacity-80 p-8 rounded-lg shadow-lg max-w-2xl w-full mx-auto', form.processing ? 'opacity-60 pointer-events-none' : '']">
              <h2 class="font-brandon-grotesque-black text-3xl md:text-4xl text-white mb-8 text-center leading-tight">Rejestracja</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                      <InputLabel for="first_name" value="Imię" class="text-white" />
                      <TextInput id="first_name" v-model="form.first_name" type="text" class="mt-1 block w-full" placeholder="np. Jan" required />
                      <InputError class="mt-2" :message="Array.isArray(form.errors.first_name) ? form.errors.first_name[0] : form.errors.first_name" />
                  </div>
                  <div>
                      <InputLabel for="last_name" value="Nazwisko" class="text-white" />
                      <TextInput id="last_name" v-model="form.last_name" type="text" class="mt-1 block w-full" placeholder="np. Kowalski" required />
                      <InputError class="mt-2" :message="Array.isArray(form.errors.last_name) ? form.errors.last_name[0] : form.errors.last_name" />
                  </div>
                  <div>
                      <InputLabel for="phone" value="Telefon" class="text-white" />
                      <TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full" placeholder="+48 987 654 321" />
                      <InputError class="mt-2" :message="Array.isArray(form.errors.phone) ? form.errors.phone[0] : form.errors.phone" />
                  </div>
                  <div>
                      <InputLabel for="email" value="Adres email" class="text-white" />
                      <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" placeholder="jan.kowalski@gmail.com" required />
                      <InputError class="mt-2" :message="Array.isArray(form.errors.email) ? form.errors.email[0] : form.errors.email" />
                  </div>
                  <div>
                      <InputLabel for="city" value="Miasto" class="text-white" />
                      <TextInput id="city" v-model="form.city" type="text" class="mt-1 block w-full" placeholder="Warszawa" />
                      <InputError class="mt-2" :message="Array.isArray(form.errors.city) ? form.errors.city[0] : form.errors.city" />
                  </div>
                  <div>
                      <InputLabel for="apartment" value="Lokal" class="text-white" />
                      <TextInput id="apartment" v-model="form.apartment" type="text" class="mt-1 block w-full" placeholder="Pod Palmami" />
                      <InputError class="mt-2" :message="Array.isArray(form.errors.apartment) ? form.errors.apartment[0] : form.errors.apartment" />
                  </div>
              </div>
              <div class="mt-8 space-y-4">
                  <div>
                      <label class="flex items-start text-white">
                          <Checkbox v-model:checked="form.consent_personal_data" required class="mt-1" />
                          <span class="ml-2 text-xs">
                              Zgoda na przetwarzanie danych osobowych.<br/>
                              <span class="text-gray-400">Wyrażam zgodę na przetwarzanie moich danych osobowych zawartych w formularzu rejestracyjnym przez [Nazwa firmy/organizacji] ... (zaznaczenie wymagane do kontynuacji rejestracji)</span>
                          </span>
                      </label>
                      <InputError class="mt-2" :message="Array.isArray(form.errors.consent_personal_data) ? form.errors.consent_personal_data[0] : form.errors.consent_personal_data" />
                  </div>
                  <div>
                      <label class="flex items-start text-white">
                          <Checkbox v-model:checked="form.consent_email" required class="mt-1" />
                          <span class="ml-2 text-xs">
                              Zgoda na przesyłanie informacji drogą elektroniczną ...<br/>
                              <span class="text-gray-400">Wyrażam zgodę na otrzymywanie od [Nazwa firmy/organizacji] informacji ... (zaznaczenie wymagane do kontynuacji rejestracji)</span>
                          </span>
                      </label>
                      <InputError class="mt-2" :message="Array.isArray(form.errors.consent_email) ? form.errors.consent_email[0] : form.errors.consent_email" />
                  </div>
                  <div>
                      <label class="flex items-start text-white">
                          <Checkbox v-model:checked="form.consent_marketing" class="mt-1" />
                          <span class="ml-2 text-xs">
                              Zgoda na przetwarzanie danych w celach marketingowych ...<br/>
                              <span class="text-gray-400">Wyrażam zgodę na przetwarzanie moich danych osobowych przez ... (zgoda dobrowolna, aczkolwiek konieczna, aby wziąć udział w Konkursie)</span>
                          </span>
                      </label>
                      <InputError class="mt-2" :message="Array.isArray(form.errors.consent_marketing) ? form.errors.consent_marketing[0] : form.errors.consent_marketing" />
                  </div>
              </div>
              <div class="flex flex-col items-center mt-8">
                  <PrimaryButton class="py-3 text-lg bg-white text-black font-bold flex items-center justify-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                      <svg v-if="form.processing" class="animate-spin mr-2 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                      </svg>
                      <span>Wyślij</span>
                  </PrimaryButton>
                  <Link :href="route('login')" class="mt-4 underline text-sm text-gray-300 hover:text-white">
                      Masz już konto? Zaloguj się
                  </Link>
              </div>
          </form>
        </AuthenticationCard>
      </div>
    </div>
</template>
