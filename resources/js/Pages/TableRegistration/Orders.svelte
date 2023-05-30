<script context="module">
    export { default as layout } from "../../Layouts/TableRegistrationLayout.svelte";
</script>

<script>
    import OrderCart from "@/Components/OrderCart.svelte";
    import Menu from "@/Components/Menu.svelte";
    import axios from "axios";
    import {onMount} from "svelte";
    import HelpRequest from "@/Components/HelpRequest.svelte";

    let registration_data, can_order = false;
    let cartDishAdded, orderCookieCleared;

    onMount(async () => {
        await handleOrderData();
        await handleCanOrder();
    });

    function onCartDishAdded(event) {
        cartDishAdded(event.detail.dish_id);
    }

    function onOrderPlaced(event) {
        const data = event.detail.data.data;
        axios.post(`/table-registration/add-order/${data['id']}`, {withCredentials: true})
            .then(async response => {
                await handleOrderData();
                await handleCanOrder();
            });
    }

    async function handleOrderData() {
        axios.get('/table-registration/data/').then(response => {
            registration_data = response.data.registration_data;
        });
    }

    async function handleStopOrder() {
        const confirmed = confirm("Zeker weten? Dit kan niet ongedaan gemaakt worden");
        if (!confirmed) return;

        axios.post(`/cart/clear-order-cookie`, {withCredentials: true}).then(res => {
            axios.post(`/table-registration/clear-cookie`, {withCredentials: true}).then(res => {
                window.location.href = '/table-registration';
            });
        });
    }

    async function handleCanOrder() {
        axios.get('/table-registration/can-order/').then(response => {
            can_order = response.data;
            if (can_order) {
                axios.post(`/cart/clear-order-cookie`, {withCredentials: true})
                    .then(async response => {
                        orderCookieCleared()
                    });
            }
        });
    }
</script>

<svelte:head>
    <title>Bestellingen</title>
</svelte:head>

{#if registration_data}
<div class="flex flex-col w-full relative p-4">
    <button on:click={handleStopOrder} class="border border-primary py-2 text-md uppercase font-bold px-4 mt-8 w-fit">Bestellen beëindigen</button>
    <HelpRequest table_id="{registration_data['table']['id']}"/>
    <div class="mt-4">
        <h2 class="text-2xl font-bold text-left">Tafelnummer: {registration_data['table']['table_number']}</h2>
        <h1 class="text-4xl font-bold text-primary text-left">Bestellingen: {registration_data['orders'].length}</h1>
        {#if registration_data['orders'].length > 0}
            <div class="grid grid-cols-2 bg-white mt-4">
                {#each registration_data['orders'] as order}
                    <p class="text-lg">Bestelnummer: {order['id']}</p>
                {/each}
            </div>
        {:else}
            <p class="font-bold text-lg mt-4">U heeft nog geen bestellingen geplaatst.</p>
        {/if}
    </div>
    <div class="w-full flex flex-col mt-8">
        {#if can_order}
            <div class="w-full p-4">
                <OrderCart on:orderPlaced={onOrderPlaced} bind:handleOrderCookieCleared={orderCookieCleared} bind:handleCartDishAdded={cartDishAdded} is_takeaway={false} />
            </div>
            <div class="w-full">
                <Menu addable={true} on:cartDishAdded={onCartDishAdded} />
            </div>
        {:else}
            <div class="flex flex-col justify-start">
                <p class="font-bold text-lg mt-4 text-primary text-left">Je kan momenteel geen nieuwe bestellingen plaatsen!</p>
                <button on:click={handleCanOrder} class="bg-primary text-white py-2 text-xl uppercase border-none font-bold px-4 mt-8 w-fit">Nieuwe bestelling starten</button>
            </div>
        {/if}
    </div>
</div>
{/if}
