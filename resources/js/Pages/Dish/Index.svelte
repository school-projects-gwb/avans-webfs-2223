<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    import Menu from "@/Components/Menu.svelte";

    export let dishes;
</script>

<div class="p-8">
    <Menu/>
</div>

