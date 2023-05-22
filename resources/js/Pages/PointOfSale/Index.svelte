<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
import axios from "axios";
import {onMount} from "svelte";
import PosMenuItem from "@/Components/PosMenuItem.svelte";
import PosCartItem from "@/Components/PosCartItem.svelte";

export let sortable = false;

let menu_data,
    cart_data,
    errors,
    sort_order = 'none';

onMount(async () => {
    if (!sortable) sort_order = 'disabled';
    await handleMenuData();
    await handleCartData();
});

async function handleMenuData() {
    axios.get('/menu/data/' + sort_order).then(response => {
        menu_data = response.data;
    });
}

async function handleCartData() {
    axios.get('/cart/data').then(response => {
        cart_data = response.data;
    });
}

export const handleCartDishAdded = async (event) => {
    axios.post(`/cart/handle-dish-cookie/${event.detail.dish_id}/1`, {withCredentials: true})
        .then(async response => {
            await handleCartData();
        });
}

async function handleCartDishRemoved(event) {
    axios.post(`/cart/handle-dish-cookie/${event.detail.dish_id}/${event.detail.amount}`, {withCredentials: true})
        .then(async response => {
            await handleCartData();
        });
}

async function removeAllFromOrder(){
    axios.post(`/cart/clear-order-cookie`, {withCredentials: true})
        .then(async response => {
            await handleCartData();
        });
}

function handlePlaceOrder() {
    errors = "";
    axios.post(`/cart/place-order`, {withCredentials: true})
        .catch(error => {
            errors = error.response.data;
        })
        .then(async response => {
            removeAllFromOrder();
        });
}

</script>



<svelte:head>
    <title>The Golden Dragon</title>
</svelte:head>

{#if menu_data && cart_data}
<div class="p-12 h-full overflow-hidden">
    <div class="grid grid-cols-2 gap-4 p-6 rounded-md bg-white">
        <div class="max-h-[calc(100vh-13.111rem)] p-4 overflow-y-scroll border border-blue-500 rounded">
            {#each Object.entries(menu_data.dish_data) as [category, dish_data]}
                <p class="font-bold text-xl text-center">{category}</p>
                {#each dish_data.dishes as dish}
                    <PosMenuItem dish={dish} on:cartDishAdded={handleCartDishAdded}/>
                {/each}
            {/each}
        </div>
        <div class="overflow-hidden">
            <div class="h-[85%] border border-blue-500 rounded py-6 overflow-y-scroll">
                <p class="font-bold text-xl text-center">Bestelling</p>
                <div class="flex flex-col gap-4 mt-4">
                    {#each cart_data.dish_data as dish}
                    <PosCartItem dish={dish} option_data={cart_data.option_data[dish.id]} on:refreshCartData={handleCartData} on:cartDishRemoved={handleCartDishRemoved}/>
                    {/each}
                </div>
            </div>
            <div class="h-[15%] border border-blue-500 rounded flex items-center justify-center p-6">
                <div class="flex justify-between w-[90%]">
                    <span class="text-2xl font-bold">Totaal:</span>

                    <div>
                        <span class="text-2xl font-bold mr-20">€ {cart_data.total_amount}</span>
                        <button class="bg-gray-100 p-[2px] px-3 border border-black" on:click={handlePlaceOrder}>Afrekenen</button>
                        <button class="bg-gray-100 p-[2px] px-3 border border-black" on:click={removeAllFromOrder}>Wis Bestelling</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
