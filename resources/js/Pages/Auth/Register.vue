<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

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

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit" class="bg-black bg-opacity-80 p-8 rounded-lg shadow-lg max-w-2xl mx-auto border border-gray-500">
            <h2 class="text-3xl font-bold text-center mb-8 text-white">Rejestracja</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <InputLabel for="first_name" value="Imię" class="text-white" />
                    <TextInput id="first_name" v-model="form.first_name" type="text" class="mt-1 block w-full" placeholder="np. Jan" required />
                    <InputError class="mt-2" :message="form.errors.first_name" />
                </div>
                <div>
                    <InputLabel for="last_name" value="Nazwisko" class="text-white" />
                    <TextInput id="last_name" v-model="form.last_name" type="text" class="mt-1 block w-full" placeholder="np. Kowalski" required />
                    <InputError class="mt-2" :message="form.errors.last_name" />
                </div>
                <div>
                    <InputLabel for="phone" value="Telefon" class="text-white" />
                    <TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full" placeholder="+48 987 654 321" />
                    <InputError class="mt-2" :message="form.errors.phone" />
                </div>
                <div>
                    <InputLabel for="email" value="Adres email" class="text-white" />
                    <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" placeholder="jan.kowalski@gmail.com" required />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div>
                    <InputLabel for="city" value="Miasto" class="text-white" />
                    <TextInput id="city" v-model="form.city" type="text" class="mt-1 block w-full" placeholder="Warszawa" />
                    <InputError class="mt-2" :message="form.errors.city" />
                </div>
                <div>
                    <InputLabel for="apartment" value="Lokal" class="text-white" />
                    <TextInput id="apartment" v-model="form.apartment" type="text" class="mt-1 block w-full" placeholder="Pod Palmami" />
                    <InputError class="mt-2" :message="form.errors.apartment" />
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
                    <InputError class="mt-2" :message="form.errors.consent_personal_data" />
                </div>
                <div>
                    <label class="flex items-start text-white">
                        <Checkbox v-model:checked="form.consent_email" required class="mt-1" />
                        <span class="ml-2 text-xs">
                            Zgoda na przesyłanie informacji drogą elektroniczną ...<br/>
                            <span class="text-gray-400">Wyrażam zgodę na otrzymywanie od [Nazwa firmy/organizacji] informacji ... (zaznaczenie wymagane do kontynuacji rejestracji)</span>
                        </span>
                    </label>
                    <InputError class="mt-2" :message="form.errors.consent_email" />
                </div>
                <div>
                    <label class="flex items-start text-white">
                        <Checkbox v-model:checked="form.consent_marketing" class="mt-1" />
                        <span class="ml-2 text-xs">
                            Zgoda na przetwarzanie danych w celach marketingowych ...<br/>
                            <span class="text-gray-400">Wyrażam zgodę na przetwarzanie moich danych osobowych przez ... (zgoda dobrowolna, aczkolwiek konieczna, aby wziąć udział w Konkursie)</span>
                        </span>
                    </label>
                    <InputError class="mt-2" :message="form.errors.consent_marketing" />
                </div>
            </div>
            <div class="flex flex-col items-center mt-8">
                <PrimaryButton class="w-full py-3 text-lg bg-white text-black font-bold" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Wyślij
                </PrimaryButton>
                <Link :href="route('login')" class="mt-4 underline text-sm text-gray-300 hover:text-white">
                    Masz już konto? Zaloguj się
                </Link>
            </div>
        </form>
    </AuthenticationCard>
</template>
