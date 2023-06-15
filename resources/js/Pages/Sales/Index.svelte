<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
import {useForm} from "@inertiajs/svelte";
import axios from "axios";

let showModal = false,
    modalContent = "",
    salesData = null;

const defaultStartDate = new Date('2020/04/11').toISOString().split('T')[0];
const defaultEndDate = new Date('2020/04/13').toISOString().split('T')[0];

let start_date = defaultStartDate,
    end_date = defaultEndDate;

async function handleGetOverview() {
    const requestData = {
        'start_date': start_date,
        'end_date': end_date
    };

    axios.post('/admin/sales/data', requestData, { withCredentials: true })
        .then(response => {
            if (!response.data) return;
            console.log(response.data);
            salesData = response.data;
        })
        .catch(error => {
            modalContent = Object.values(error.response.data.errors).join('\n');
            showModal = true;
        });
}


</script>

<svelte:head>
    <title>Verkoop overzicht</title>
</svelte:head>

<div>
    <div class="flex p-4 border-b-2 border-blue-600 m-2">
        <form class="w-1/2 2xl:w-4/12 mr-4 border border-blue-600 p-4 rounded-md flex flex-row items-center">
            <div class="flex flex-col">
                <div>
                    <label class="inline-block mr-1">Begindatum:</label>
                    <input name="start_date" class="py-0 float-right" type="date" bind:value={start_date} />
                </div>
                <div class="mt-2">
                    <label class="inline-block mr-1">Einddatum:</label>
                    <input name="end_date" class="py-0 float-right" type="date" bind:value={end_date} />
                </div>
            </div>
            <a on:click={handleGetOverview} class="block ml-4 bg-blue-100 py-4 px-1 border border-blue-600 rounded-lg text-blue-600 font-bold cursor-pointer">Maak Overzicht</a>
        </form>
        <div class="w-1/2 2xl:w-8/12 border border-blue-600 p-4 rounded-md flex justify-between font-bold text-2xl">
            <div>Omzet:</div>
            <div>€ {salesData ? salesData['total_gross'] : '0,00'}</div>
            <div>BTW:</div>
            <div>€ {salesData ? salesData['total_vat'] : '0,00'}</div>
            <div>excl. BTW:</div>
            <div>€ {salesData ? salesData['total_net'] : '0,00'}</div>
        </div>
    </div>

    <div class="flex p-4 border border-blue-600 mx-6 rounded-md justify-center">
        <table class="w-1/2">
            <thead class="border-b-2 border-blue-600">
                <th class="w-1/12 border-l border-r border-blue-600">Datum</th>
                <th class="w-1/2 border-l border-r border-blue-600">Gerecht</th>
                <th class="w-1/12 border-l border-r border-blue-600">Prijs</th>
                <th class="w-1/12 border-l border-r border-blue-600">Aantal</th>
                <th class="w-1/12 border-l border-r border-blue-600">Subtotaal</th>
            </thead>
            <tbody>
            {#if salesData}
                {#each Object.entries(salesData['orderLines']) as [index, order_line]}
                    <tr>
                        <td>{order_line['created_at']}</td>
                        <td>
                            {order_line['dish_name']}
                            <span class="italic text-sm">{order_line['option_names']}</span>
                        </td>
                        <td>€ {(order_line['combined_price'] / order_line['amount']).toFixed(2)}</td>
                        <td>{order_line['amount']}</td>
                        <td>€ {order_line['combined_price'].toFixed(2)}</td>
                    </tr>
                {/each}
            {/if}
            </tbody>
        </table>
    </div>
</div>

{#if showModal}
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <div class="fixed inset-0 bg-gray-800 opacity-50"></div>
        <div class="bg-white p-6 w-1/2 flex justify-between relative">
            <div class="mt-4">
                <p>
                    {modalContent}
                </p>
            </div>
            <button class="text-gray-500 hover:text-gray-400 font-bold absolute top-6 right-4 text-xl mt-2 mr-2" on:click={() => showModal = false}>
                X
            </button>
        </div>
    </div>
{/if}
