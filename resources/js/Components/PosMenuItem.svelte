<script>
    import { createEventDispatcher } from 'svelte';
    export let dish;
    const dispatch = createEventDispatcher();

    function handleCart(dish_id) {
        dispatch('cartDishAdded', {
            dish_id: dish_id
        });
    }
</script>

<div class="flex py-[2px]">
    <div class="flex w-full gap-14">
        <span>{dish.menu_number}.</span>
        <div>
            <span>{dish.name}</span>
            {#if dish.description != null && dish.description !== ""}
                <div class="w-full text-left flex justify-between">
                    <p class="italic text-sm">({dish.description})</p>
                </div>
            {/if}
        </div>

    </div>

    <div class="flex justify-end gap-10 w-full">
        <p>€{dish.price}</p>
        <button class="bg-gray-100 p-[2px] px-3 border border-black" on:click={handleCart(dish.id)}>Toevoegen</button>
    </div>

</div>
