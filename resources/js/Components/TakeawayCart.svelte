<script>
    import axios from 'axios';
    import { onMount } from 'svelte';

    let cart_data;

    onMount(async () => {
        await handleCartData();
    });

    async function handleCartData() {
        axios.get('/cart/takeaway/data').then(response => {
            cart_data = response.data;
            console.log(cart_data.dish_data)
        });
    }

    export const handleCartDishAdded = async (dish_id) => {
        axios.post(`/cart/takeaway/handle-dish-cookie/${dish_id}`, {withCredentials: true})
            .then(async response => {
                await handleCartData();
            });
    }
</script>

<div class="fixed bottom-0 right-0 w-1/4 bg-white p-4">
    <h1 class="font-bold text-xl mb-4 text-primary text-left uppercase">Afhaal bestelling plaatsen</h1>
    {#if cart_data && cart_data.dish_data.length > 0}
        <div>
            <h1 class="text-2xl font-bold text-left">Stap 1: Gerechten</h1>
            {#each cart_data.dish_data as dish}
                <div class="flex flex-col even:bg-gray-100 p-1">
                    <div class="flex justify-between">
                        <p class="text-lg font-bold">
                            {dish.menu_number == null ? '' : dish.menu_number}{dish.menu_addition == null ? '' : dish.menu_addition }{dish.menu_number != null || dish.menu_addition != null ? '.' : ''}
                            {dish.name}
                        </p>
                        <p class="text-lg font-bold">€ {dish.price}</p>
                    </div>
                    {#if dish.description != null}
                        <p class="text-left italic">{dish.description}</p>
                    {/if}
                    <div class="text-left mt-4">
                        {#if dish.option_amount}
                            {#if dish.options.filter(option => option.price === null).length > 0}
                                <p class="underline">Kies {dish.option_amount} optie(s)</p>
                                {#each dish.options.filter(option => option.price === null) as option}
                                    <div>
                                        <input type="checkbox">
                                        {option.name} {option.condition_text ? `(${option.condition_text})` : ''}
                                    </div>
                                {/each}
                            {/if}
                            {#if dish.options.filter(option => option.price !== null).length > 0}
                                <p class="underline">Optioneel</p>
                                {#each dish.options.filter(option => option.price !== null) as option}
                                    <div>
                                        <input type="checkbox">
                                        {option.name} {option.condition_text ? `(${option.condition_text})` : ''}
                                        <b>- € {option.price}</b>
                                    </div>
                                {/each}
                            {/if}
                        {/if}
                    </div>
                </div>
            {/each}
        </div>

        <div class="flex flex-col text-left mt-8">
            <h1 class="text-2xl font-bold text-left">Stap 2: Uw gegevens</h1>
            <label for="first_name">Voornaam</label>
            <input id="first_name" class="mb-4" type="text"/>
            <label for="last_name">Achternaam</label>
            <input id="last_name" type="text"/>
            <button class="bg-primary text-white py-2 text-xl uppercase border-none font-bold mt-8">Plaats bestelling</button>
        </div>
    {:else}
        Uw bestelling is leeg. Voeg producten toe uit het menu om je bestelling te starten!
    {/if}
</div>
