<script setup>
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    business: Object,
});

const form = useForm({
    name: "",
    avg_service_time: 10,
});

const submitQueue = () => {
    form.post(`/businesses/${props.business.slug}/queues`);
};
</script>

<template>
    <div>
        <h1>{{ business.name }}</h1>
        <p>{{ business.location }}</p>

        <hr />

        <h2>Queues</h2>

        <ul>
            <li v-for="queue in business.queues" :key="queue.id">
                {{ queue.name }} ({{ queue.avg_service_time }} min)
                <a :href="`/queues/${queue.slug}`">Open</a>
            </li>
        </ul>

        <hr />

        <h3>Create Queue</h3>

        <form @submit.prevent="submitQueue">
            <input v-model="form.name" placeholder="Queue name" />
            <input v-model="form.avg_service_time" type="number" />

            <button>Create Queue</button>
        </form>
    </div>
</template>
