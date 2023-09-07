<template>
    <div class="col-12">
        <div class="row pb-5">
            <div class="col-5">
                <div><h2>Trends</h2></div>
            </div>

            <div class="col-2 mx-0">
                <VueDatePicker
                    v-model="inputdate"
                    :enable-time-picker="false"
                ></VueDatePicker>
                <!-- :format="format" -->
            </div>
            <div class="col-3 mx-0">
                <input
                    type="text"
                    v-model="keywords"
                    class="form-control keyword-input"
                />
            </div>
            <div class="col-2 mx-0">
                <button class="btn btn-sm btn-primary" @click="changePropValue">
                    Search
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <TweetsByDate />
            </div>
            <div class="col-6">
                <div class="col-12 shadow-sm mx-0 py-4">
                    <ul
                        class="cloud"
                        role="navigation"
                        aria-label="Webdev tag cloud"
                    >
                        <li v-for="(word, index) in words" :key="index">
                            <a
                                :data-weight="RandomWeight()"
                                @click="selectedWord(word.text)"
                                >{{ word.text }}</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <TweetAnalysisTable :filter="searchFilter" />
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { defineComponent, ref, onMounted } from "vue";

import TweetAnalysisTable from "./tweetAnalysisTable.vue";

import TweetsByDate from "./tweetsByDate.vue";

import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

export default defineComponent({
    name: "sentiment-analysis-timelines-index",
    layout: AdminLayout,

    components: {
        VueDatePicker,
        TweetAnalysisTable,
        TweetsByDate,
    },
    setup() {
        const searchFilter = ref({
            date: null,
            keywords: "",
        });

        const inputdate = ref(null);
        const keywords = ref(null);

        const changePropValue = () => {
            searchFilter.value = {
                date: inputdate.value,
                keywords: keywords.value,
            };
        };

        const words = ref([]);
        const getData = async () => {
            const res = await axios.get(`/admin/sentiments/trends/wordclouds`);
            if (res.status === 200) {
                words.value = res.data;
            }
        };
        const RandomWeight = () => {
            return Math.floor(Math.random() * 3) + 1;
        };

        const selectedWord = (word) => {
            keywords.value = word;
        };

        onMounted(async () => {
            getData();
        });
        return {
            changePropValue,
            searchFilter,
            keywords,
            inputdate,
            words,
            RandomWeight,
            selectedWord,
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
    /*   
  Not supported by any browser at the moment :(
  --size: attr(data-weight number); 
  */
    --size: 4;
    --color: #a33;
    color: var(--color);
    font-size: calc(var(--size) * 0.25rem + 0.5rem);
    display: block;

    position: relative;
    text-decoration: none;
    cursor: pointer;
    /* 
  For different tones of a single color
  opacity: calc((15 - (9 - var(--size))) / 15); 
  */
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
