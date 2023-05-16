<script>
    import axios from 'axios';
    import { onMount } from 'svelte';

    onMount(async () => {
        console.log('hi');
    });

    export const handleCartDishAdded = (dish_id) => {
        console.log(dish_id);
    }
</script>

<div class="fixed bottom-0 right-0 text-white text-4xl">
    Hi
</div>
