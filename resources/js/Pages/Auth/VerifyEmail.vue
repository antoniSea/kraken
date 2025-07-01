<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    status: String,
});

const form = useForm({});
const feedback = ref(null);
const feedbackType = ref('success');

const submit = () => {
    feedback.value = null;
    form.post(route('verification.send'), {
        onSuccess: () => {
            feedback.value = 'Nowy link weryfikacyjny został wysłany na Twój adres email.';
            feedbackType.value = 'success';
        },
        onError: () => {
            feedback.value = 'Nie udało się wysłać linku. Spróbuj ponownie.';
            feedbackType.value = 'error';
        },
    });
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Head title="Email Verification" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="mb-4 text-sm text-gray-600">
            Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </div>

        <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600">
            A new verification link has been sent to the email address you provided in your profile settings.
        </div>

        <form @submit.prevent="submit" :class="['bg-black bg-opacity-80 p-8 rounded-lg shadow-lg max-w-2xl mx-auto border border-gray-500', form.processing ? 'opacity-60 pointer-events-none' : '']">
            <h2 class="text-3xl font-bold text-center mb-8 text-white">Weryfikacja adresu email</h2>
            <div v-if="feedback" :class="[feedbackType === 'success' ? 'bg-green-500' : 'bg-red-500', 'text-white p-4 mb-4 rounded text-center']">
                {{ feedback }}
            </div>
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton class="w-full py-3 text-lg bg-white text-black font-bold flex items-center justify-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    <svg v-if="form.processing" class="animate-spin mr-2 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    <span>Wyślij ponownie link weryfikacyjny</span>
                </PrimaryButton>
                <div class="ml-4">
                    <Link
                        :href="route('profile.show')"
                        class="underline text-sm text-gray-300 hover:text-white"
                    >
                        Edytuj profil</Link>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-300 hover:text-white ml-2"
                    >
                        Wyloguj się
                    </Link>
                </div>
            </div>
        </form>
    </AuthenticationCard>
</template>
