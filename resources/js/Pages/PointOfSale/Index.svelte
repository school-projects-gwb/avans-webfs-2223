<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
import axios from "axios";
import {onMount} from "svelte";
import PosMenuItem from "@/Components/PosMenuItem.svelte";
import PosCartItem from "@/Components/PosCartItem.svelte";
import DishSearchBar from "@/Components/DishSearchBar.svelte";

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
    axios.post(`/cart/place-order/0`, {withCredentials: true})
        .catch(error => {
            errors = error.response.data;
        })
        .then(async response => {
            removeAllFromOrder();
            alert("Bestelling is doorgevoerd en doorgegeven aan de keuken!");
        });
}

</script>



<svelte:head>
    <title>The Golden Dragon</title>
</svelte:head>

{#if menu_data && cart_data}
<div class="p-12 h-full overflow-hidden">
    <DishSearchBar bind:menu_data={menu_data}></DishSearchBar>
    <div class="grid grid-cols-2 gap-4 px-6 pb-6 min-h-fit rounded-b-md bg-white">
        <div class="max-h-[calc(100vh-13.111rem)] min-h-[25rem] p-4 overflow-y-scroll border border-blue-500 rounded">
            {#each Object.values(menu_data.dish_data) as category_data}
                {#if category_data.dishes.length > 0}
                <p class="text-center text-xl font-semibold">{ category_data.name }</p>
                {/if}
                {#each category_data.dishes as dish}
                    <PosMenuItem dish={dish} on:cartDishAdded={handleCartDishAdded}/>
                {/each}
            {/each}

            {#if Object.values(menu_data.dish_data).every(category => category.dishes.length === 0)}
                <div class="h-full flex justify-center items-center">
                    <p class="text-center font-semibold">Er zijn geen gerechten gevonden</p>
                </div>

            {/if}
        </div>
        <div class="overflow-hidden">
            <div class="h-[85%] border border-blue-500 rounded py-6 overflow-y-scroll">
                <p class="font-bold text-xl text-center">Bestelling</p>
                <div class="flex flex-col gap-4 mt-4">
                    {#if cart_data.dish_data.length > 0}
                        {#each cart_data.dish_data as dish}
                            <PosCartItem dish={dish} option_data={cart_data.option_data[dish.id]} on:refreshCartData={handleCartData} on:cartDishRemoved={handleCartDishRemoved}/>
                        {/each}
                    {:else}
                        <p class="text-center font-semibold mt-4">Er zijn nog geen gerechten toegevoegd aan de bestelling</p>
                    {/if}

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
