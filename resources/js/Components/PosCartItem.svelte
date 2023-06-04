<script>
    import { createEventDispatcher } from 'svelte';
    import axios from "axios";

    export let dish,option_data;
    const dispatch = createEventDispatcher();

    function handleAmountRemoval(dish_id){
        dispatch('cartDishRemoved', {
            dish_id: dish_id,
            amount: option_data.amount
        });
    }

    function handleRemoval(dish_id){
        dispatch('cartDishRemoved', {
            dish_id: dish_id,
            amount: 0
        });
    }

    function isSelectedOption(dish_id, option_id) {
        return option_data.options.includes(option_id.toString());
    }

    async function handleOption(dish_id, option_id) {
        axios.post(`/cart/handle-dish-option-cookie/${dish_id}/${option_id}`, {withCredentials: true})
            .then(async response => {
                dispatch('refreshCartData');
            });
    }


</script>

<div class="flex w-[90%] m-auto flex-col">
    <div class="flex flex-row justify-between">
        <div class="flex gap-4 justify-center items-center">
            <p>{dish.menu_number == null ? '' : dish.menu_number}{dish.menu_addition == null ? '' : dish.menu_addition }{dish.menu_number != null || dish.menu_addition != null ? '.' : ''}</p>
            <p>{dish.name}</p>
        </div>
        <div class="flex gap-4 justify-center items-center">

            <input type="number" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   bind:value={option_data.amount}
                   on:change={handleAmountRemoval(dish.id)}>
            <p class="">€{dish.price}</p>

            <button on:click={() => handleRemoval(dish.id)} class="bg-gray-100 p-[2px] px-3 py-2 border border-black hover:text-red-500" ><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256"><path fill="currentColor" d="M216 50h-42V40a22 22 0 0 0-22-22h-48a22 22 0 0 0-22 22v10H40a6 6 0 0 0 0 12h10v146a14 14 0 0 0 14 14h128a14 14 0 0 0 14-14V62h10a6 6 0 0 0 0-12ZM94 40a10 10 0 0 1 10-10h48a10 10 0 0 1 10 10v10H94Zm100 168a2 2 0 0 1-2 2H64a2 2 0 0 1-2-2V62h132Zm-84-104v64a6 6 0 0 1-12 0v-64a6 6 0 0 1 12 0Zm48 0v64a6 6 0 0 1-12 0v-64a6 6 0 0 1 12 0Z"/></svg></button>
        </div>
    </div>
    <div>
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
                    <div class="flex flex-row">
                        <input type="checkbox" class="disabled:bg-gray-300 mr-2" on:click={handleOption(dish.id, option.id)} checked={isSelectedOption(dish.id, option.id)}
                               disabled={!isSelectedOption(dish.id, option.id) && dish.is_option_optional_limit ? 'disabled' : ''}
                        >
                        {option.name} {option.condition_text ? `(${option.condition_text})` : ''}
                        <b> - € {option.price}</b>
                    </div>
                {/each}
            {/if}
        {/if}
    </div>

</div>
