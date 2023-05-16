<script>
    import axios from 'axios';
    import {createEventDispatcher, onMount} from 'svelte';

    // Indicates whether menu is sortable and supports favouriting dishes
    // This is not relevant in i.e. the employee back-end environment
    // So it is disabled by default
    export let sortable = false;

    let menu_data,
        sort_order = 'none',
        sort_input_value;

    const dispatch = createEventDispatcher();

    onMount(async () => {
        if (!sortable) sort_order = 'disabled';
        await handleMenuData();
    });

    async function handleMenuData() {
        axios.get('/menu/data/' + sort_order).then(response => {
            menu_data = response.data;
        });
    }

    function handleSort(new_sort_order) {
        if (sort_order == 'disabled' || sort_order == new_sort_order) return;
        handleMenuData();
    }

    function isFavourite(dish_id) {
        return menu_data.favourite_dishes.includes(dish_id.toString());
    }

    function handleFavourite(dish_id) {
        axios.post(`/menu/handle-dish-cookie/${dish_id}`, {withCredentials: true})
            .then(response => {
                handleMenuData();
            });
    }

    function handleCart(dish_id) {
        dispatch('cartDishAdded', {
            dish_id: dish_id
        });
    }
</script>

{#if menu_data}
    {#if sortable}
        <div class="w-full flex flex-col justify-start mb-4 p-8">
            <a class="underline text-left mb-2 text-xl font-bold" href="/menu/print-pdf" target="_blank">Download menu PDF</a>
            <label class="text-left font-bold">Menu sorteren <span class="text-sm italic">(alfabetische volgorde)</span></label>
            <select class="w-full md:w-1/2 lg:w-1/4" bind:value={sort_input_value} on:change={handleSort}>
                {#each Object.entries(menu_data.sort_options) as [key, value]}
                    <option value="{key}">{value}</option>
                {/each}
            </select>
            <p class="text-left mt-2 italic font-semibold">Klik op het selectievakje naast een gerecht om deze toe te voegen/verwijderen aan je favorieten!</p>
        </div>
    {/if}
    <div class="bg-menu relative overflow-scroll">
        <div class="columns-3 min-w-[1300px] border-4 border-green-700 m-8 p-4 select-none">
            <div class="col-span-1 flex flex-col mx-4 mt-4 p-2 bg-yellow-50 rounded-lg">
                <p class="tracking-widest uppercase">Menukaart</p>
                <h3 class="font-bold text-2xl">Chinees Indische Specialiteiten</h3>
                <h2 class="font-bold text-3xl">De Gouden Draak</h2>
                <div class="grid grid-cols-2 mt-4">
                    <div>
                        <p class="font-bold">Openingstijden</p>
                        <p>
                            {#each menu_data.restaurant_data.opening_times_grouped as opening_time_group}
                                {@html opening_time_group}
                            {/each}
                        </p>
                    </div>
                    <div>
                        {@html menu_data.restaurant_data.menu_description}
                    </div>
                    <div class="col-span-2 text-left">
                        <b class="text-lg font-bold mt-2">Allergieën? Meld het ons!</b>
                        <p class="text-md italic">Onze producten kunnen kruisbesmetting bevatten.</p>
                        <p class="mt-2"><b>Telefoonnummer:</b> {menu_data.restaurant_data.phone_number}</p>
                        <p><b>E-mailadres:</b> {menu_data.restaurant_data.email_address}</p>
                    </div>
                </div>
            </div>
            {#each Object.entries(menu_data.dish_data) as [category, category_content]}
                <div class="col-span-1 flex flex-col mx-4 mt-4 p-2 {category_content.special_description != null ? 'bg-white border border-green-700 ' : ''}">
                    <b>{category}</b>
                    {#if category_content.special_description != null}
                        <p class="my-2 text-lg leading-tight">{category_content.special_description}</p>
                    {/if}
                    <div class="grid grid-cols-1 grid-row">
                        {#each category_content.dishes as dish}
                            <div class="w-full text-left flex justify-between">
                                <span>
                                    {dish.menu_number == null ? '' : dish.menu_number}{dish.menu_addition == null ? '' : dish.menu_addition }{dish.menu_number != null || dish.menu_addition != null ? '.' : ''}
                                    {dish.name}
                                    {#if sortable}
                                        <input type="checkbox" class="h-3 w-3" checked={isFavourite(dish.id)} on:click={handleFavourite(dish.id)} />
                                        <input class="text-sm font-bold underline ml-1 cursor-pointer" type="button" on:click={handleCart(dish.id)} value="+ Bestelling" />
                                    {/if}
                                </span>
                                <span class="w-0 flex-1 border-b-2 border-black border-dotted mb-1.5 mx-1"></span>
                                <span>€ {dish.price}</span>
                            </div>
                            {#if dish.description != null && dish.description !== ""}
                            <div class="w-full text-left flex justify-between">
                                <p class="italic text-sm">({dish.description})</p>
                            </div>
                            {/if}
                            {#if dish.is_discount == true && dish.options.length > 0 && dish.option_required != null}
                                <p class="text-sm w-full text-left underline">Maak een keuze uit {dish.option_amount} van onderstaande keuzegerechten:</p>
                                <div class="w-full text-left flex justify-between">
                                    <p class="italic text-sm">
                                    {#each dish.options as option}
                                        {#if option.price == null}
                                            {option.name},&nbsp
                                        {/if}
                                    {/each}
                                    </p>
                                </div>
                            {/if}
                        {/each}
                    </div>
                </div>
            {/each}
            <div class="col-span-1 flex flex-col mx-4 mt-4 p-2">
                <b>EXTRA'S</b>
                {#each menu_data.option_data as option}
                    <p class="w-full text-left">{option.name} ({option.condition_text}) - € {option.price} extra</p>
                {/each}
            </div>
        </div>
    </div>
{/if}


