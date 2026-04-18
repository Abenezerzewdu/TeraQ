<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    location: "",
});

const submit = () => {
    form.post("/businesses");
};
</script>

<template>
    <div>
        <h1>Create Business</h1>

        <form @submit.prevent="submit">
            <input v-model="form.name" placeholder="Business name" />
            <div v-if="form.errors.name">{{ form.errors.name }}</div>

            <input v-model="form.location" placeholder="Location" />

            <button type="submit">Create</button>
        </form>
    </div>
</template>
