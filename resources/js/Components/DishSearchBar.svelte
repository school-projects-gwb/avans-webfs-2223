<script>
    import {writable} from "svelte/store";

    export let menu_data;

    let searchQuery = '';
    let filterOption = '';
    const categories = Object.keys(menu_data.dish_data);
    const menuDataCopy = { ...menu_data };

    const handleSearch = () => {
        const filtered = Object.values(menuDataCopy.dish_data).filter(category => filterOption === '' || category.name === filterOption);

        menu_data.dish_data = filtered.map(category => {
            const filteredDishes = category.dishes.filter(dish => {
                const dishIdCombo = `${dish.menu_number}${dish.menu_addition}`;
                const formattedSearchQuery = searchQuery.toUpperCase();
                return dish.name.toUpperCase().includes(formattedSearchQuery) ||
                    dishIdCombo.includes(formattedSearchQuery) ||
                    (dish.menu_addition && dish.menu_addition.includes(formattedSearchQuery)) ||
                    (dish.menu_number && dish.menu_number.toString().includes(formattedSearchQuery));
            });

            return {
                ...category,
                dishes: filteredDishes
            };
        });
    };

    function capitalizeFirstLetter(string) {
        return string[0].toUpperCase() + string.toLowerCase().slice(1);
    }

</script>

<div class="w-full p-6 rounded-t-md bg-white">
    <div class="w-1/2">
        <input class="w-2/6 shadow appearance-none border rounded w-full py-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" bind:value={searchQuery} placeholder="Voer gerechtnaam of gerechtnummer in">
        <select  class="w-2/6 shadow appearance-none border rounded w-full py-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" bind:value={filterOption}>
            <option value="">Alle categorieën</option>
            {#each categories as category}
                <option value="{ category }">{ capitalizeFirstLetter(category) }</option>
            {/each}
        </select>
        <button class="w-1/6 bg-gray-100 py-1.5 px-3 border border-black" on:click={handleSearch}>Zoeken</button>
    </div>
</div>
