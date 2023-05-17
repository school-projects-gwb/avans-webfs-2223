<script context="module">
    export { default as layout } from "../Layouts/SiteLayout.svelte";
</script>

<script>
    import Menu from "@/Components/Menu.svelte";
    import OrderCart from "@/Components/OrderCart.svelte";
    import OrderTakeawaySuccess from "@/Components/OrderTakeawaySuccess.svelte";

    let cartDishAdded, orderData, orderCookieCleared;
    function onCartDishAdded(event) {
        cartDishAdded(event.detail.dish_id);
    }

    function onOrderPlaced(event) {
        orderData(event.detail.data);
    }

    function onOrderCookieCleared(event) {
        orderCookieCleared(event);
    }
</script>

<svelte:head>
    <title>The Golden Dragon</title>
</svelte:head>

<Menu sortable={true} on:cartDishAdded={onCartDishAdded} />
<div class="fixed bottom-0 right-0 w-1/4 bg-white p-4">
    <OrderCart on:orderPlaced={onOrderPlaced} bind:handleOrderCookieCleared={orderCookieCleared} bind:handleCartDishAdded={cartDishAdded} is_takeaway={true} />
    <OrderTakeawaySuccess on:orderCookieCleared={onOrderCookieCleared} bind:handleOrderPlaced={orderData} />
</div>

