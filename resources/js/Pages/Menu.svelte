<script context="module">
    export { default as layout } from "../Layouts/SiteLayout.svelte";
</script>

<script>
    import Menu from "@/Components/Menu.svelte";
    import OrderCart from "@/Components/OrderCart.svelte";

    let cartDishAdded
    function onCartDishAdded(event) {
        cartDishAdded(event.detail.dish_id);
    }
</script>

<svelte:head>
    <title>The Golden Dragon</title>
</svelte:head>

<Menu sortable={true} on:cartDishAdded={onCartDishAdded} />
<div class="fixed bottom-0 right-0 w-1/4 bg-white p-4">
    <OrderCart bind:handleCartDishAdded={cartDishAdded} is_takeaway={true} />
</div>

