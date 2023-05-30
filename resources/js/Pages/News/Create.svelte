<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    import {useForm} from "@inertiajs/svelte";
    import InputError from "@/Components/InputError.svelte";
    import TextInput from "@/Components/TextInput.svelte";
    import InputLabel from "@/Components/InputLabel.svelte";
    import TextArea from "@/Components/TextArea.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";

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

            <form class="max-w-xl flex flex-col mt-4" on:submit|preventDefault={submitCreate}>
                <div>
                    <InputLabel for="title" value="Titel" />
                    <TextInput id="title" type="text" bind:value={$createForm.title} required />
                    <InputError message={$createForm.errors.title} />
                </div>

                <div class="mt-4">
                    <InputLabel for="content" value="Inhoud" />
                    <TextArea id="content" type="text" bind:value={$createForm.content} required />
                    <InputError message={$createForm.errors.content} />
                </div>

                <PrimaryButton disabled={$createForm.processing} classes="mt-4">
                    Nieuwsbericht aanmaken
                </PrimaryButton>
            </form>
        </div>
    </div>
</div>
