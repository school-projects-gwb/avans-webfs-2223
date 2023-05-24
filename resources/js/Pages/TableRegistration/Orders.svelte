<script context="module">
    export { default as layout } from "../../Layouts/TableRegistrationLayout.svelte";
</script>

<script>
    import OrderCart from "@/Components/OrderCart.svelte";
    import Menu from "@/Components/Menu.svelte";
    import axios from "axios";
    import {onMount} from "svelte";

    let registration_data, can_order = false;

    onMount(async () => {
        await handleOrderData();
        await handleCanOrder();
    });

    let cartDishAdded, orderCookieCleared;

    function onCartDishAdded(event) {
        cartDishAdded(event.detail.dish_id);
    }

    function onOrderPlaced(event) {
        const data = event.detail.data;
    }

    function onOrderCookieCleared(event) {
        orderCookieCleared(event);
    }

    async function handleOrderData() {
        axios.get('/table-registration/data/').then(response => {
            registration_data = response.data.registration_data;
        });
    }

    async function handleCanOrder() {
        axios.get('/table-registration/can-order/').then(response => {
            can_order = response.data;
        });
    }
</script>

{#if registration_data}
<div class="flex flex-col mt-16 w-full relative p-4">
    <div>
        <h2 class="text-2xl font-bold text-left">Tafelnummer: {registration_data['table']['table_number']}</h2>
        <h1 class="text-4xl font-bold text-primary text-left">Bestellingen</h1>
        {#if registration_data['orders'].length > 0}

        {:else}
            <p class="font-bold text-lg mt-4">U heeft nog geen bestellingen geplaatst.</p>
        {/if}
    </div>
    <div class="w-full flex mt-8">
        {#if can_order}
        <div class="w-1/2">
            <Menu addable={true} on:cartDishAdded={onCartDishAdded} />
        </div>
        <div class="w-1/2 p-4">
            <OrderCart on:orderPlaced={onOrderPlaced} bind:handleOrderCookieCleared={orderCookieCleared} bind:handleCartDishAdded={cartDishAdded} is_takeaway={false} />
        </div>
        {:else}
            <div class="flex flex-col justify-start">
                <p class="font-bold text-lg mt-4 text-primary text-left">Je kan momenteel geen nieuwe bestellingen plaatsen!</p>
                <button on:click={handleCanOrder} class="bg-primary text-white py-2 text-xl uppercase border-none font-bold px-4 mt-8 w-fit">Probeer opnieuw</button>
            </div>
        {/if}
    </div>
</div>
{/if}
