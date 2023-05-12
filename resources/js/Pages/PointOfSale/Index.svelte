<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    export let menu_data;
    console.log(menu_data)
</script>



<svelte:head>
    <title>The Golden Dragon</title>
</svelte:head>

<!--<div class="bg-white overflow-hidden shadow-sm rounded-lg w-full p-6">-->
<div class="p-12 h-full overflow-hidden">
    <div class="grid grid-cols-2 gap-4 p-6 rounded-md bg-white">
        <div class="max-h-[calc(100vh-13.111rem)] p-4 overflow-y-scroll border border-blue-500 rounded">
            {#each Object.entries(menu_data) as [category, category_content]}
                <p class="font-bold text-xl text-center">{category}</p>
                {#each category_content as dish}
                    <div class="flex">
                        <div class="flex w-full gap-14">
                            <p>{dish.menu_number}.</p>
                            <p>{dish.name}</p>
                        </div>

                        <div class="flex justify-end gap-10 w-full">
                            <p>€{dish.price}</p>
                            <button>Toevoegen</button>
                        </div>

                    </div>

                {/each}
            {/each}
        </div>
        <div>
            asdsd
        </div>
    </div>


</div>

