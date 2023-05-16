<script context="module">
    export { default as layout } from "../Layouts/SiteLayout.svelte";
</script>

<script>
    import Menu from "@/Components/Menu.svelte";
    import TakeawayCart from "@/Components/TakeawayCart.svelte";

    let cartDishAdded
    function onCartDishAdded(event) {
        cartDishAdded(event.detail.dish_id);
    }
</script>

<svelte:head>
    <title>The Golden Dragon</title>
</svelte:head>

<Menu sortable={true} on:cartDishAdded={onCartDishAdded} />
<TakeawayCart bind:handleCartDishAdded={cartDishAdded} />

