<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import ActionMessage from "@/Components/ActionMessage.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    todos: Array,
});

const form = useForm({
    title: '',
});

const doneform = useForm({
    done: true,
});

function add() {
    form.post(route('todo.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => form.title = ''
    })
}

function done(todo) {
    doneform.done = !todo.done
    doneform.put(route('todo.update', [todo.id]), {
        preserveScroll: true,
        preserveState: true,
    })
}

</script>

<template>
    <AppLayout title="Todo">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Todo
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 dark:bg-gray-800 dark:text-gray-100 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-col space-y-6">
                        <form @submit.prevent="add()" class="flex flex-col">
                            <div class="flex w-full space-x-4">
                                <div class="w-full flex items-center">
                                    <TextInput
                                        id="title"
                                        ref="title"
                                        v-model="form.title"
                                        type="text"
                                        placeholder="Enter a title"
                                        class="mt-1 block w-full"
                                    />
                                </div>
                                <div class="flex items-center">
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Add
                                    </PrimaryButton>
                                </div>
                            </div>
                            <div class="ml-1 mt-1">
                                <InputError :message="form.errors.title" class="mt-2" />
                                <ActionMessage :on="form.recentlySuccessful" class="me-3">
                                    Saved.
                                </ActionMessage>
                            </div>
                        </form>
                        <div v-for="todo in todos.data.filter((t) => !t.done)" class="flex flex-col cursor-pointer" @click="done(todo)">
                            <p class="font-bold text-xl">
                                <s v-if="todo.done" class="text-gray-400">{{ todo.title }}</s>
                                <span v-else>{{ todo.title }}</span>
                            </p>
                            <span class="text-xs text-gray-400">{{ todo.created_at }}</span>
                        </div>
                        <div v-for="todo in todos.data.filter((t) => t.done)" class="flex flex-col cursor-pointer" @click="done(todo)">
                            <p class="font-bold text-xl">
                                <s v-if="todo.done" class="text-gray-400">{{ todo.title }}</s>
                                <span v-else>{{ todo.title }}</span>
                            </p>
                            <span class="text-xs text-gray-400">{{ todo.created_at }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
