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

    function toggleCart() {
        const cart_window = document.getElementById('cart_window');
        const cart_button = document.getElementById('cart_button');
        cart_window.classList.toggle('hidden');
        cart_button.classList.toggle('hidden');
    }
</script>

<svelte:head>
    <title>The Golden Dragon</title>
</svelte:head>

<Menu sortable={true} addable={true} on:cartDishAdded={onCartDishAdded} />
<button class="fixed bottom-0 right-0 bg-menu text-2xl font-bold hidden uppercase underline p-4 border-4 border-green-700" id="cart_button" on:click={toggleCart}>Afhaal bestelling plaatsen</button>
<div class="fixed bottom-0 right-0 w-full md:w-3/4 lg:w-5/12 xl:w-1/4 bg-menu p-4 border-4 border-green-700" id="cart_window">
    <div class="w-full flex justify-end">
        <button class="text-right underline font-bold cursor-pointer" on:click={toggleCart}>Venster verkleinen</button>
    </div>
    <OrderCart on:orderPlaced={onOrderPlaced} bind:handleOrderCookieCleared={orderCookieCleared} bind:handleCartDishAdded={cartDishAdded} is_takeaway={true} />
    <OrderTakeawaySuccess on:orderCookieCleared={onOrderCookieCleared} bind:handleOrderPlaced={orderData} />
</div>

