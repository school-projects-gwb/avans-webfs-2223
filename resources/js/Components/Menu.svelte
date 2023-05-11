<script>
    import axios from 'axios';
    import { onMount } from 'svelte';

    let menu_data;

    onMount(async () => {
        axios.get('/menu/data').then(response => {
            menu_data = response.data;
        });
    });
</script>

{#if menu_data}
    <div class="bg-menu relative overflow-scroll">
        <div class="columns-3 min-w-[1200px] border-4 border-green-700 m-8 p-4 pointer-events-none select-none">
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
                                            {option.name}&nbsp
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


