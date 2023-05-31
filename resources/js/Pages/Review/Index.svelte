<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    export let reviews;
    console.log(reviews);
</script>

<svelte:head>
    <title>Reviews</title>
</svelte:head>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 block"
        >
            <h1 class="text-4xl font-bold block">Reviews</h1>

            <div class="flex flex-col">
                {#each reviews as review}
                    <div class="border border-2 mt-4 p-4">

                        <h2 class="text-xl font-bold">Bestelnummer {review.order_id}</h2>
                        <span class="text-xs"><b>Gemaakt op</b> {review.created_at}</span>
                        {#each review.review_questions as review_question}
                            <p>{review_question['question'].question}: <b class="text-primary">{review_question.answer} sterren</b></p>
                        {/each}
                        {#if review.comment != null}
                            <p class="font-bold text-lg mt-4">Extra commentaar</p>
                            <p>{review.comment}</p>
                        {/if}
                    </div>
                {/each}
            </div>
        </div>
    </div>
</div>
