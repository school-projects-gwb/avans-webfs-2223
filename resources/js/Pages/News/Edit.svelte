<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    import {useForm} from "@inertiajs/svelte";
    import InputError from "@/Components/InputError.svelte";
    import InputLabel from "@/Components/InputLabel.svelte";
    import TextInput from "@/Components/TextInput.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import TextArea from "@/Components/TextArea.svelte";

    export let news_article;

    const deleteForm = useForm({
        id: news_article.id
    });

    const editForm = useForm({
        id: news_article.id,
        title: news_article.title,
        content: news_article.content
    });

    const submitEdit = () => {
        $editForm.put(route("news.update", {news: news_article.id}));
    };

    const submitDelete = () => {
        const confirmed = confirm("Zeker weten? Dit kan niet ongedaan gemaakt worden");
        if (!confirmed) return;

        $deleteForm.delete(route("news.destroy", {news: news_article.id}));
    };
</script>

<svelte:head>
    <title>Nieuwsbericht bewerken</title>
</svelte:head>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8"
        >
            <a class="underline" href="{route('news.index')}">Terug naar overzicht</a>
            <h1 class="text-4xl font-bold mt-4">Nieuwsartikel bewerken</h1>

            <form class="flex flex-col mt-4" on:submit|preventDefault={submitEdit}>
                <div>
                    <InputLabel for="title" value="Titel" />
                    <TextInput id="title" type="text" bind:value={$editForm.title} required />
                    <InputError message={$editForm.errors.title} />
                </div>

                <div class="mt-4">
                    <InputLabel for="content" value="Inhoud" />
                    <TextArea id="content" type="text" bind:value={$editForm.content} required />
                    <InputError message={$editForm.errors.content} />
                </div>

                <PrimaryButton disabled={$editForm.processing} classes="mt-4">
                    Nieuwsbericht opslaan
                </PrimaryButton>
            </form>
            <form class="mt-4" on:submit|preventDefault={submitDelete}>
                <input type="submit" class="border border-primary text-primary mt-4 w-fit text-md text-center py-2 px-8 uppercase cursor-pointer" value="Verwijderen">
            </form>
        </div>
    </div>
</div>
