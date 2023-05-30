<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    import Menu from "@/Components/Menu.svelte";

    export let dishes;

    async function onCartDishAdded(event) {
        const dish_id = event.detail.dish_id;
        console.log(dish_id);
    }
</script>

<svelte:head>
    <title>Gerechten</title>
</svelte:head>

<div class="p-8">
    <Menu addable={true} addedText="Beheren" on:cartDishAdded={onCartDishAdded} />
</div>

