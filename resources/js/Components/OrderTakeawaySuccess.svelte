<script>
    import axios from "axios";
    import {createEventDispatcher, onMount} from "svelte";
    import QrCode from "svelte-qrcode"

    let orderPlaced = false;
    let orderData, orderId;

    onMount(async () => {
        await handleQrCode();
    });

    const dispatch = createEventDispatcher();

    async function handleQrCode() {
        axios.get('/cart/is-order-placed').then(response => {
            orderPlaced = response.data['order'] != null;

            if (!orderPlaced) return;
            orderData = response.data['order'];

            let orderLineData = '';
            for (let orderLine of orderData['order_lines']) {
                orderLineData += `dish-${orderLine['dish_id']}_option-${orderLine['option_id']}_x${orderLine['amount']}`;
            }

            orderId = orderData['id'];
            orderData = `${orderData['first_name']}_${orderData['last_name']}_#${orderData['id']}_`;
            orderData += orderLineData;
        });
    }

    async function handleNewOrder() {
        axios.post(`/cart/clear-order-cookie`, {withCredentials: true})
            .then(async response => {
                orderPlaced = false;
                dispatch('orderCookieCleared');
            });
    }

    async function handlePrintQr() {
        await axios.post(`/cart/print-order-qr`, {withCredentials: true})
    }

    export const handleOrderPlaced = async (orderData) => {
        await handleQrCode();
    }
</script>

{#if orderPlaced}
    <div class="flex flex-col">
        <h1 class="text-4xl font-bold text-primary text-left">Bestelling succesvol geplaatst!</h1>
        <div class="w-1/2 my-4">
            <QrCode value="{orderData}" />
            <a href="/cart/print-order-qr" target="_blank" class="text-left block underline font-bold mt-4 text-lg">QR code printen</a>
        </div>
        <button class="bg-primary text-white py-2 px-4 text-xl uppercase border-none font-bold mt-4" on:click={handleNewOrder}>Nieuwe bestelling starten</button>
    </div>
{/if}
