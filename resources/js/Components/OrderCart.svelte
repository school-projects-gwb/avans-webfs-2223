<script>
    import axios from 'axios';
    import {createEventDispatcher, onMount} from 'svelte';

    // Component configuration
    // Pass these variables during the initialisation of the component
    export let is_takeaway = false;

    let cart_data, first_name, last_name, errors = "";

    const dispatch = createEventDispatcher();

    onMount(async () => {
        await handleCartData();
    });

    async function handleCartData() {
        axios.get('/cart/data').then(response => {
            cart_data = response.data;
        });
    }

    async function handleOption(dish_id, option_id) {
        axios.post(`/cart/handle-dish-option-cookie/${dish_id}/${option_id}`, {withCredentials: true})
            .then(async response => {
                await handleCartData();
            });
    }

    function isSelectedOption(dish_id, option_id) {
        return cart_data.option_data[dish_id]['options'].includes(option_id.toString());
    }

    export const handleCartDishAdded = async (dish_id) => {
        axios.post(`/cart/handle-dish-cookie/${dish_id}/1`, {withCredentials: true})
            .then(async response => {
                await handleCartData();
            });
    }

    async function handleCartDishRemoved(dish_id, amount) {
        const input = document.getElementById(`amount-${dish_id}`);
        const value = input.value;
        axios.post(`/cart/handle-dish-cookie/${dish_id}/${value}`, {withCredentials: true})
            .then(async response => {
                await handleCartData();
            });
    }

    function handlePlaceOrder() {
        const requestData = {
            'first_name': first_name,
            'last_name': last_name
        };

        axios.post(`/cart/place-order`, requestData, {withCredentials: true})
            .then(async response => {
                dispatch('orderPlaced', {
                    data: response
                });
            })
            .catch(error => {
                errors = error.response.data;
            });
    }
</script>
<div>
    <h1 class="font-bold text-xl mb-4 text-primary text-left uppercase">{is_takeaway ? 'Afhaal' : ''} bestelling plaatsen</h1>
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
                        <div class="flex">
                            <p class="text-lg font-bold">€ {dish.price}</p>
                            <input id="amount-{dish.id}" class="inline w-20 h-6 ml-2 mt-0.5" type="number" min="0" value="{cart_data.option_data[dish.id]['amount']}" on:change={handleCartDishRemoved(dish.id)}/>
                        </div>
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
                                        <input type="checkbox" class="disabled:bg-gray-300" on:click={handleOption(dish.id, option.id)} checked={isSelectedOption(dish.id, option.id)}
                                           disabled={!isSelectedOption(dish.id, option.id) && dish.is_option_required_limit ? 'disabled' : ''}
                                        >
                                        {option.name} {option.condition_text ? `(${option.condition_text})` : ''}
                                    </div>
                                {/each}
                            {/if}
                            {#if dish.options.filter(option => option.price !== null).length > 0}
                                <p class="underline">Optioneel</p>
                                {#each dish.options.filter(option => option.price !== null) as option}
                                    <div>
                                        <input type="checkbox" class="disabled:bg-gray-300" on:click={handleOption(dish.id, option.id)} checked={isSelectedOption(dish.id, option.id)}
                                           disabled={!isSelectedOption(dish.id, option.id) && dish.is_option_optional_limit ? 'disabled' : ''}
                                        >
                                        {option.name} {option.condition_text ? `(${option.condition_text})` : ''}
                                        <b>- € {option.price}</b>
                                    </div>
                                {/each}
                            {/if}
                        {/if}
                    </div>
                </div>
            {/each}
            <div class="flex justify-between mt-4">
                <p class="text-xl font-bold">
                    Totaalbedrag
                </p>
                <p class="text-xl font-bold">€ {cart_data.total_amount}</p>
            </div>
        </div>


        <div class="flex flex-col text-left mt-8">
            {#if is_takeaway}
                <h1 class="text-2xl font-bold text-left">Stap 2: Uw gegevens</h1>
                <label for="first_name">Voornaam</label>
                <input id="first_name" class="mb-4" type="text" bind:value={first_name}/>
                <label for="last_name">Achternaam</label>
                <input id="last_name" type="text" bind:value={last_name}/>
            {/if}
            <span class="text-primary mt-8 font-bold text-lg">{errors}</span>
            <button class="bg-primary text-white py-2 text-xl uppercase border-none font-bold" on:click={handlePlaceOrder}>Plaats bestelling</button>
        </div>
    {:else}
        Bestelling is leeg. Voeg producten toe uit het menu om je bestelling te starten!
    {/if}
</div>
