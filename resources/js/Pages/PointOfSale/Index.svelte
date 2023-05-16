<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    export let menu_data;

    let orderData = [];
    let totalPrice = 0.00;

    function addToOrder(dish){
        orderData = [...orderData, dish];
        calculateTotal();
    }

    function removeFromOrder(dish){
        orderData = orderData.filter(item => item !== dish);
        calculateTotal();
    }

    function resetOrder(){
        orderData = [];
        calculateTotal();
    }

    function calculateTotal(){
        totalPrice = 0.00;
        orderData.forEach(item => {
            totalPrice = parseFloat(totalPrice) + parseFloat(item.price);
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
                <p class="font-bold text-xl text-center">{category}</p>
                {#each category_content as dish}
                    <div class="flex py-[2px]">
                        <div class="flex w-full gap-14">
                            <p>{dish.menu_number}.</p>
                            <p>{dish.name}</p>
                        </div>

                        <div class="flex justify-end gap-10 w-full">
                            <p>€{dish.price}</p>
                            <button class="bg-gray-100 p-[2px] px-3 border border-black" on:click={addToOrder(dish)}>Toevoegen</button>
                        </div>

                    </div>

                {/each}
            {/each}
        </div>
        <div class="overflow-hidden">
            <div class="h-[85%] border border-blue-500 rounded py-6 overflow-y-scroll">
                <p class="font-bold text-xl text-center">Bestelling</p>
                <div class="flex flex-col gap-4 mt-4">
                    {#if orderData.length == 0}
                        <p class="text-center">Er zijn nog geen gerechten toegevoegd aan de bestelling.</p>
                    {/if}
                    {#each orderData as dish}
                        <div class="flex justify-between w-[90%] m-auto">
                            <div class="flex gap-4">
                                <p>{dish.menu_number}.</p>
                                <p>{dish.name}</p>
                            </div>
                            <div class="flex gap-4">
                                <p>€{dish.price}</p>
                                <button class="bg-gray-100 p-[2px] px-3 border border-black" on:click={removeFromOrder(dish)}>Verwijderen</button>
                            </div>
                        </div>
                    {/each}
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

