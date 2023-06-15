<script setup>
    import { Link } from "@inertiajs/svelte";

    export let href = null,
        active = false;

    const classes = "inline-flex h-fit items-center px-4 xl:px-8 py-0 rounded-lg border border-blue-600 text-blue-600 font-bold bg-blue-100";
</script>

<Link {href} class={classes}>
    <slot />
</Link>
