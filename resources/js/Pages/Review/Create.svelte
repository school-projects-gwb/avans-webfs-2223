<script>
    import {Heart} from "lucide-svelte";
    import axios from "axios";

    export let order_id, questions, max_rating;

    let comment, errors = "";

    function handleRatingHighlight(event, isCancel = false) {
        const target = event.target;

        const split = target.id.split('-');
        const selectedUntil = split[split.length - 1];
        const checkboxIdPrefix = `${split[0]}-${split[1]}`;

        for (let i = 0; i <= selectedUntil; i++) {
            const checkbox = document.getElementById(`${checkboxIdPrefix}-${i}`);
            checkbox.parentElement.children[1].style.color = isCancel ? '' : 'GoldenRod';
        }
    }

    function handleRatingHighlightClear(event) {
        setTimeout(handleRatingHighlight(event, true), 1000);
    }

    function handleRatingChange(event) {
        const target = event.target;

        const split = target.id.split('-');
        const selectedUntil = split[split.length - 1];
        const checkboxIdPrefix = `${split[0]}-${split[1]}`;

        let counter = 0;
        while (document.getElementById(`${checkboxIdPrefix}-${counter}`)) {
            document.getElementById(`${checkboxIdPrefix}-${counter}`).parentElement.children[1].style.fill = '';
            document.getElementById(`${checkboxIdPrefix}-${counter}`).checked = false;
            counter++;
        }

        target.checked = true;

        for (let i = 0; i <= selectedUntil; i++) {
            const checkbox = document.getElementById(`${checkboxIdPrefix}-${i}`);
            checkbox.parentElement.children[1].style.fill = 'red';
        }
    }

    function handleReviewSubmit() {
        const reviewData = {
            questions: [],
            comment: comment,
        };

        for (let question of questions) {
            const answer = document.querySelector(`.cbx-${question.id}:checked`)?.value;
            if (answer) {
                reviewData.questions.push({
                    question_id: question.id,
                    answer: answer,
                });
            }
        }

        axios.post(`/reviews/store/${order_id}`, reviewData, { withCredentials: true })
            .then(async response => {
                const data = response.data;
                data === '' ? window.location.href= '/reviews/success' : errors = response.data;
            });
    }
</script>

<div class="mt-12 p-6 w-full lg:w-1/2 max-w-5xl m-auto rounded-xl">
    <div>
        <h2 class="tracking-widest uppercase text-gray-500 ml-0.5">De Gouden Draak</h2>
        <h1 class="text-primary text-4xl font-semibold tracking-tight">Bedankt voor uw bestelling!</h1>
        <h3 class="text-xl mt-4 mb-8 text-gray-800">Vul hieronder uw beoordeling van ons restaurant in</h3>
    </div>

    {#each questions as question}
        <div class="my-8 lg:my-12 text-lg">
            <p class="font-bold">{question.question}</p>
            <div class="flex">
                {#each Array(max_rating) as _, i}
                    <div class="relative flex">
                        <input class="w-8 h-8 absolute rounded-full cursor-pointer mr-2 opacity-0 cbx-{question.id}"
                               type="checkbox"
                               id="cbx-{question.id}-{i}"
                               name="question-{question.id}[]"
                               value="{i+1}"
                               on:mouseover={handleRatingHighlight}
                               on:mouseleave={handleRatingHighlightClear}
                               on:click={handleRatingChange}
                        >
                        <Heart size="36" class="heartIcon z-10 pointer-events-none mr-2"/>
                    </div>
                {/each}
            </div>
        </div>
    {/each}

    <p class="font-bold mt-12 text-lg">Heeft u nog opmerkingen of tips?</p>
    <textarea bind:value={comment} class="w-full mt-2 border-2 border-gray-300 rounded-xl resize-none max-w-xl"></textarea>

    <button on:click={handleReviewSubmit} class="block mt-8 bg-primary text-white font-bold text-xl py-3 px-8 rounded-xl">Beoordeling versturen!</button>
    <span class="text-primary mt-4 text-lg uppercase block">{errors}</span>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap');

    * {
        font-family: 'Quicksand', sans-serif;
    }
</style>
