<template>
    <div class="col-12 px-0">
        <div
            v-if="!loading"
            class="col-12 shadow-border mx-0 py-4"
            style="min-height: 292px"
        >
            <ul class="cloud" role="navigation" aria-label="Webdev tag cloud">
                <li v-for="(word, index) in words" :key="index">
                    <a
                        :data-weight="RandomWeight()"
                        @click="selectedWord(word.text)"
                        >{{ word.text }}</a
                    >
                </li>
            </ul>
        </div>
        <div v-else class="col-12 shadow-border mx-0 py-4">
            <div
                class="col-12 text-center"
                style="height: 300px; padding-top: 100px"
            >
                <img :src="LoadingGif" width="50" />
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import LoadingGif from "../../../../assets/loading.gif";

import { useFilterStore } from "../../../../stores/filter";

export default defineComponent({
    name: "sentiment-analysis-timelines-index",

    components: {},
    setup() {
        const filterStore = useFilterStore();

        const loading = ref(true);

        const words = ref([]);
        const getData = async () => {
            const res = await axios.get(`/admin/sentiments/trends/wordclouds`);
            if (res.status === 200) {
                words.value = res.data;
                loading.value = false;
            }
        };
        const RandomWeight = () => {
            return Math.floor(Math.random() * 3) + 1;
        };

        const selectedWord = (word) => {
            filterStore.keywords = word;
        };

        onMounted(async () => {
            getData();
        });
        return {
            words,
            RandomWeight,
            selectedWord,
            LoadingGif,
            loading,
        };
    },
});
</script>

<style scoped>
.keyword-input {
    height: 36px !important;
    border: 1px solid #dddddd !important;
}
.btn-primary {
    background-color: #144f9f;
    border: none;
    height: 36px;
}

ul.cloud {
    list-style: none;
    padding-left: 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    width: 100%;
}

ul.cloud a {
    --size: 4;
    --color: #a33;
    color: var(--color);
    font-size: calc(var(--size) * 0.25rem + 0.5rem);
    display: block;

    position: relative;
    text-decoration: none;
    cursor: pointer;
}

ul.cloud a[data-weight="1"] {
    --size: 1;
}
ul.cloud a[data-weight="2"] {
    --size: 2;
}
ul.cloud a[data-weight="3"] {
    --size: 3;
}
ul.cloud a[data-weight="4"] {
    --size: 4;
}
ul.cloud a[data-weight="5"] {
    --size: 6;
}
ul.cloud a[data-weight="6"] {
    --size: 8;
}
ul.cloud a[data-weight="7"] {
    --size: 10;
}
ul.cloud a[data-weight="8"] {
    --size: 13;
}
ul.cloud a[data-weight="9"] {
    --size: 16;
}

ul[data-show-value] a::after {
    content: " (" attr(data-weight) ")";
    font-size: 1rem;
}

ul.cloud li:nth-child(2n + 1) a {
    --color: #181;
}
ul.cloud li:nth-child(3n + 1) a {
    --color: #33a;
}
ul.cloud li:nth-child(4n + 1) a {
    --color: #c38;
}

ul.cloud a:focus {
    outline: 1px dashed;
}

ul.cloud a::before {
    content: "";
    position: absolute;
    top: 0;
    width: 0;
    height: 100%;
    background: var(--color);
    opacity: 0.15;
    transition: width 0.25s;
}

ul.cloud a:focus::before,
ul.cloud a:hover::before {
    width: 100%;
}

@media (prefers-reduced-motion) {
    ul.cloud * {
        transition: none !important;
    }
}
</style>
