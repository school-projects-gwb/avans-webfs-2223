<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    import {useForm} from "@inertiajs/svelte";
    import InputError from "@/Components/InputError.svelte";

    const createForm = useForm({
        title: "",
        content: ""
    });

    const submitCreate = () => {
        $createForm.post(route("news.store"));
    };
</script>

<svelte:head>
    <title>Nieuwsbericht aanmaken</title>
</svelte:head>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8"
        >
            <a class="underline" href="{route('news.index')}">Terug naar overzicht</a>
            <h1 class="text-4xl font-bold mt-4">Nieuwsartikel aanmaken</h1>

            <form class="flex flex-col mt-4" on:submit|preventDefault={submitCreate}>
                <label for="title">Titel</label>
                <input id="title" type="text" bind:value={$createForm.title}/>
                <InputError message={$createForm.errors.title} />

                <label for="content">Inhoud</label>
                <textarea id="content" class="mt-4" bind:value={$createForm.content}></textarea>
                <InputError message={$createForm.errors.content} />

                <input type="submit" class="bg-primary text-white mt-4 w-fit text-xl text-center py-2 px-8 uppercase cursor-pointer" value="Opslaan">
            </form>
        </div>
    </div>
</div>
