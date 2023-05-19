<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    import {writable} from "svelte/store";

    export let menu_data;

    let orderData = writable([]);
    let totalPrice = 0.00;

    for(const category in menu_data){
        const arrayData = menu_data[category];
        arrayData.forEach(dish => {
            dish.amount = 1;
        })
    }

    function addToOrder(dish) {
        orderData.update(items => {
            const existingItem = items.find(item => item.name === dish.name);
            if (existingItem) {
                existingItem.amount++;
            } else {
                items = [...items, {...dish}];
            }
            return items;
        });
        calculateTotal();
    }

    function removeFromOrder(dish) {
        orderData.update(items => {
            const existingItem = items.find(item => item.dishData === dish.dishData);
            return items = items.filter(item => item !== dish);
        })
        calculateTotal();
    }


    function resetOrder(){
        orderData.update(() => {
            return [];
        })
        calculateTotal();
    }

    function calculateTotal(){
        totalPrice = 0;
        $orderData.forEach(item => {
            const dishPrice = item.price;
            const itemTotal = dishPrice * item.amount;
            totalPrice += itemTotal;
        });
    }

</script>



<svelte:head>
    <title>The Golden Dragon</title>
</svelte:head>

<div class="p-12 h-full overflow-hidden">
    <div class="grid grid-cols-2 gap-4 p-6 rounded-md bg-white">
        <div class="max-h-[calc(100vh-13.111rem)] p-4 overflow-y-scroll border border-blue-500 rounded">
            {#each Object.entries(menu_data) as [category, category_content]}
<!--                component van maken-->
                <p class="font-bold text-xl text-center">{category}</p>
                {#each category_content as dish}
                    <div class="flex py-[2px]">
                        <div class="flex w-full gap-14">
                            <p>{dish.menu_number}.</p>
                            <p>{dish.name}</p>
                        </div>

                        <div class="flex justify-end gap-10 w-full">
                            <p>€{dish.price}</p>
                            <button class="bg-gray-100 p-[2px] px-3 border border-black" on:click={() => addToOrder(dish)}>Toevoegen</button>
                        </div>

                    </div>

                {/each}
            {/each}
        </div>
        <div class="overflow-hidden">
            <div class="h-[85%] border border-blue-500 rounded py-6 overflow-y-scroll">
                <p class="font-bold text-xl text-center">Bestelling</p>
                <div class="flex flex-col gap-4 mt-4">
                    {#if $orderData.length == 0}
                        <p class="text-center">Er zijn nog geen gerechten toegevoegd aan de bestelling.</p>
                    {:else}
                        {#each $orderData as dish}
<!--                            component van maken-->
                            <div class="flex justify-between w-[90%] m-auto">
                                <div class="flex gap-4 justify-center items-center">
                                    <p>{dish.menu_number}.</p>
                                    <p>{dish.name}</p>
                                </div>
                                <div class="flex gap-4 justify-center items-center">
                                    <input type="number" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           bind:value={dish.amount}
                                           on:input={calculateTotal}>
                                    <p class="">€{dish.price}</p>
                                    <button class="bg-gray-100 p-[2px] px-3 py-2 border border-black hover:text-red-500" on:click={removeFromOrder(dish)}><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256"><path fill="currentColor" d="M216 50h-42V40a22 22 0 0 0-22-22h-48a22 22 0 0 0-22 22v10H40a6 6 0 0 0 0 12h10v146a14 14 0 0 0 14 14h128a14 14 0 0 0 14-14V62h10a6 6 0 0 0 0-12ZM94 40a10 10 0 0 1 10-10h48a10 10 0 0 1 10 10v10H94Zm100 168a2 2 0 0 1-2 2H64a2 2 0 0 1-2-2V62h132Zm-84-104v64a6 6 0 0 1-12 0v-64a6 6 0 0 1 12 0Zm48 0v64a6 6 0 0 1-12 0v-64a6 6 0 0 1 12 0Z"/></svg></button>
                                </div>
                            </div>
                        {/each}
                    {/if}

                </div>
            </div>
            <div class="h-[15%] border border-blue-500 rounded flex items-center justify-center p-6">
                <div class="flex justify-between w-[90%]">
                    <span class="text-2xl font-bold">Totaal:</span>

                    <div>
                        <span class="text-2xl font-bold mr-20">€ {totalPrice.toFixed(2)}</span>
                        <button class="bg-gray-100 p-[2px] px-3 border border-black">Afrekenen</button>
                        <button class="bg-gray-100 p-[2px] px-3 border border-black" on:click={resetOrder}>Wis Bestelling</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

