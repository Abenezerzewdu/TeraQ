<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";

const props = defineProps({
    queue: Object,
    entries: Array,
});

// ✅ Clone initial data (correct)
const entries = ref([...props.entries]);

let channel = null;

onMounted(() => {
    channel = window.Echo.channel(`queue.${props.queue.id}`).listen(
        ".user.joined",
        (e) => {
            console.log("EVENT RECEIVED:", e);

            // ✅ Prevent duplicates
            const exists = entries.value.find(
                (entry) => entry.id === e.entry.id,
            );

            if (!exists) {
                entries.value = [...entries.value, e.entry];
            }
        },
    );
});

// ✅ cleanup (important)
onUnmounted(() => {
    if (channel) {
        channel.stopListening(".user.joined");
    }
});

const form = useForm({
    name: "",
});

const join = () => {
    form.post(`/queues/${props.queue.slug}/join`, {
        preserveScroll: true,
        preserveState: true, // 🔥 VERY IMPORTANT
    });
};
</script>

<template>
    <div>
        <h1>{{ queue.name }}</h1>
        <p>Avg time: {{ queue.avg_service_time }} min</p>

        <hr />

        <h2>Join Queue</h2>

        <form @submit.prevent="join">
            <input v-model="form.name" placeholder="Your name" />
            <div v-if="form.errors.name">{{ form.errors.name }}</div>

            <button type="submit">Join</button>
        </form>

        <hr />

        <h2>Current Queue</h2>

        <ul>
            <li v-for="entry in entries" :key="entry.id">
                #{{ entry.position }} - {{ entry.user_name }} ({{
                    entry.status
                }})
            </li>
        </ul>
    </div>
</template>
