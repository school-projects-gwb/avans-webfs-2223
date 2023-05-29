<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    import axios from "axios";

    const weekdays = {
        1: 'Maandag',
        2: 'Dinsdag',
        3: 'Woensdag',
        4: 'Donderdag',
        5: 'Vrijdag',
        6: 'Zaterdag',
        7: 'Zondag'
    }
    export let planning_data;

    async function handlePlanningData() {
        axios.get('/admin/planning/data').then(response => {
            planning_data = response.data;
        });
    }

    async function handleUnassign(tableId, userId, weekday) {
        axios.post(`/admin/planning/unassign/${tableId}/${userId}/${weekday}`, {withCredentials: true})
            .then(async response => {
                await handlePlanningData();
            });
    }

    async function handleAssign(tableId, weekday) {
        const userSelect = document.getElementById(`select-${tableId}-${weekday}`);
        const userId = userSelect.value;

        axios.post(`/admin/planning/assign/${tableId}/${userId}/${weekday}`, {withCredentials: true})
            .then(async response => {
                await handlePlanningData();
            });
    }

    async function handleCreateTable() {
        axios.post(`/admin/planning/create-table`, {withCredentials: true})
            .then(async response => {
                await handlePlanningData();
            });
    }

    async function handleDestroyTable(tableId) {
        const confirmed = confirm("Zeker weten? Dit kan niet ongedaan gemaakt worden");
        if (!confirmed) return;

        axios.delete(`/admin/planning/destroy-table/${tableId}`, {withCredentials: true})
            .then(async response => {
                await handlePlanningData();
            });
    }
</script>

<div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div
            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 block"
        >
            <h1 class="text-4xl font-bold block">Tafels & Planning</h1>
            <p class="text-xl">Beheer werknemers per tafel en werkdag</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 w-full mt-8">
                {#each planning_data as table}
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h3 class="text-2xl font-bold">Tafel #<span class="text-primary">{table.table_number}</span></h3>
                        <div class="flex flex-col">
                            {#each Object.entries(table.weekdayUsers) as [weekday, users]}
                                <div class="my-1">
                                    <b class="text-lg">{weekdays[weekday]}</b>
                                    <div>
                                        {#each users.attached as user}
                                            <div class="inline bg-red-100 p-1 rounded-xl mr-2">
                                                {user.name}
                                                <span class="font-bold text-lg cursor-pointer" on:click={handleUnassign(table.id, user.id, weekday)}>x</span>
                                            </div>
                                        {/each}
                                        {#if users.notAttached.length > 0}
                                            <div class="inline bg-yellow-100 py-2 rounded-xl">
                                                <select id="select-{table.id}-{weekday}" class="py-0.5 inline bg-yellow-100 border-0">
                                                    {#each users.notAttached as user}
                                                        <option value="{user.id}">{user.name}</option>
                                                    {/each}
                                                </select>
                                                <a class="text-xl font-bold mx-2 cursor-pointer" on:click={handleAssign(table.id, weekday)}>+</a>
                                            </div>
                                        {/if}
                                    </div>
                                </div>
                            {/each}
                        </div>
                        <a class="underline font-bold mt-4 block cursor-pointer" on:click={handleDestroyTable(table.id)}>Verwijder tafel</a>
                    </div>
                {/each}
                <div class="bg-gray-50 p-4 rounded-xl flex items-center justify-center cursor-pointer hover:bg-gray-200" on:click={handleCreateTable}>
                    <h1 class="text-4xl font-bold">+ Nieuwe tafel</h1>
                </div>
            </div>
        </div>
    </div>
</div>
