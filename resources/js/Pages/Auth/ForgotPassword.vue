<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const feedback = ref(null);
const feedbackType = ref('success');

const submit = () => {
    feedback.value = null;
    form.post(route('password.email'), {
        onSuccess: () => {
            feedback.value = 'Link do resetowania hasła został wysłany na podany adres email.';
            feedbackType.value = 'success';
        },
        onError: (errors) => {
            feedback.value = 'Nie udało się wysłać linku. Sprawdź poprawność adresu email.';
            feedbackType.value = 'error';
        },
    });
};
</script>

<template>
    <Head title="Forgot Password" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" :class="['login-form p-8 rounded-lg shadow-lg max-w-2xl mx-auto border border-gray-500', form.processing ? 'opacity-60 pointer-events-none' : '']">
            <h2 class="text-3xl font-bold text-center mb-8 text-white">Przypomnij hasło</h2>
            <div v-if="feedback" :class="[feedbackType === 'success' ? 'bg-green-500' : 'bg-red-500', 'text-white p-4 mb-4 rounded text-center']">
                {{ feedback }}
            </div>
            <div class="mb-4 text-sm text-white text-center">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </div>
            <div>
                <InputLabel for="email" value="Email" class="text-white" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div class="flex items-center justify-center mt-8">
                <PrimaryButton class=" py-3 text-lg bg-white text-black font-bold flex items-center justify-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    <svg v-if="form.processing" class="animate-spin mr-2 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    <span>Wyślij link</span>
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
