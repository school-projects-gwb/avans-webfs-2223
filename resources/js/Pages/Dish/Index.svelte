<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    import Menu from "@/Components/Menu.svelte";

    async function onCartDishAdded(event) {
        const dish_id = event.detail.dish_id;
        window.location.href = `/admin/dishes/edit/${dish_id}`;
    }
</script>

<svelte:head>
    <title>Gerechten</title>
</svelte:head>

<div class="p-8">
    <a href="{route('admin.dishes.create')}" class="bg-primary text-white mt-4 w-fit text-md text-center py-2 px-8 mb-8 uppercase cursor-pointer block">Nieuw gerecht</a>
    <Menu addable={true} addedText="Beheren" on:cartDishAdded={onCartDishAdded} />
</div>

