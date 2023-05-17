<script>
    import axios from "axios";
    import {onMount} from "svelte";

    let orderPlaced = false;
    let orderData;

    onMount(async () => {
        axios.get('/cart/is-order-placed').then(response => {
            orderPlaced = response.data['order'] != null;

            if (!orderPlaced) return;

            axios.get('/cart/get-order-qr-data').then(response => {
                // todo implement
            });
        });
    });

    export const handleOrderPlaced = async (orderData) => {
        orderPlaced = true;
    }
</script>

{#if orderPlaced}
    <button class="bg-primary text-white py-2 px-4 text-xl uppercase border-none font-bold">Nieuwe bestelling starten</button>
{/if}
