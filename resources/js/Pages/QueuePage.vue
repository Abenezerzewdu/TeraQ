<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
});

const submit = () => {
    form.post(`/queues/${props.queue.id}/join`);
};
</script>

<template>
    <form @submit.prevent="submit">
        <input v-model="form.name" type="text" placeholder="Enter your name" />

        <button type="submit">Join Queue</button>

        <!-- Validation Error -->
        <div v-if="form.errors.name">
            {{ form.errors.name }}
        </div>
    </form>
</template>
