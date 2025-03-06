<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    emailValid: null, // Tracks email validation status
    emailValidationError: '', // Custom error message for email validation
});

const validateEmail = async () => {
    if (!form.email) {
        form.emailValid = false;
        form.emailValidationError = 'E-mail é obrigatório';
        return;
    }

    form.emailValidationError = '';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    try {
        const response = await fetch('/api/validate-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ email: form.email }),
        });

        const data = await response.json();
        if (response.ok) {
            form.emailValid = data.is_valid;
            form.emailValidationError = data.is_valid ? '' : 'E-mail inválido';
        } else {
            form.emailValid = false;
            form.emailValidationError = 'Erro ao validar e-mail';
        }
    } catch (error) {
        form.emailValid = false;
        form.emailValidationError = 'Erro de conexão';
        console.error('Validation error:', error);
    }
};

const submit = async () => {
    // Validate email before submitting
    if (form.emailValid === null || !form.emailValid) {
        await validateEmail();
        if (!form.emailValid) return; // Stop if email is invalid
    }

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nome" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    @blur="validateEmail"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email || form.emailValidationError" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Senha" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirme a Senha" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Já possui conta?
                </Link>

                <PrimaryButton 
                    class="ms-4" 
                    :class="{ 'opacity-25': form.processing }" 
                    :disabled="form.processing || form.emailValid === false"
                >
                    Criar conta
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
/* Optional: Style the error message */
.error {
    color: red;
    font-size: 12px;
}
</style>