<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    export let dish;
    console.log(dish);
</script>

<svelte:head>
    <title>Gerecht bewerken</title>
</svelte:head>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8"
        >
            <a class="underline" href="{route('admin.dishes.index')}">Terug naar overzicht</a>
            <h1 class="text-4xl font-bold mt-4">Gerecht bewerken</h1>
        </div>
    </div>
</div>
