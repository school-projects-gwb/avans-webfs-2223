<script>
    import axios from "axios";
    import {onMount} from "svelte";

    let orderPlaced = false;

    onMount(async () => {
        axios.get('/cart/is-order-placed').then(response => {
            orderPlaced = response.data === 1;
        });
    });

    export const handleOrderPlaced = async (orderData) => {
        orderPlaced = true;
        console.log(orderData);
    }
</script>

{#if orderPlaced}
    <button class="bg-primary text-white py-2 px-4 text-xl uppercase border-none font-bold">Nieuwe bestelling starten</button>
{/if}
