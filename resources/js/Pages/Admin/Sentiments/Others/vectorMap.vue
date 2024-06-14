<template>
    <!-- <div class="col-12 shadow mx-0">
        Count Of Location by Location and Label
    </div> -->
    <div class="col-12 mx-0 ox-0 shadow-border py-5">
        <div
            v-if="permissionError && !dataLoaded"
            class="col-12 text-center"
            style="height: 215px; padding-top: 60px; color: red"
        >
            {{ permissionError }}
        </div>
        <vuevectormap
            v-if="dataLoaded && !permissionError"
            width="100%"
            height="200"
            :options="{
                markers,
                markerStyle,
                // Map options..
                // markers: []
                // markerStyle: {}
                // etc..
            }"
        >
        </vuevectormap>
        <div v-if="!dataLoaded && !permissionError" class="col-12 text-center">
            <img :src="LoadingGif" width="50" />
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch, computed } from "vue";
import LoadingGif from "../../../../assets/loading.gif";
import { useFilterStore } from "../../../../stores/filter";

export default defineComponent({
    name: "sentiment-analysis-trends-vectorMap",

    components: {},

    setup() {
        const filterStore = useFilterStore();
        const searchFilter = computed(() => filterStore.searchFilter);
        const search = ref({
            date: "",
            keywords: "",
            sentimentTypes: "",
        });
        const markers = ref([]);
        const dataLoaded = ref(false);
        const markerStyle = ref({
            initial: {
                fontFamily: "'Inter', sans-serif",
                fontSize: 1,
                border: "none",
                fontWeight: 200,

                strokeWidth: 1,
                r: 5,
            },
            hover: {
                fill: "#0000",
            },
        });
        const permissionError = ref("");
        const getData = async () => {
            try {
                const res = await axios.post(
                    `/admin/sentiments/others/mapCoorddinates`,
                    { searchFilter: search.value }
                );
                if (res.status === 200) {
                    // res.data.forEach((item) => {
                    //     markers.value.push(item);
                    // });
                    markers.value = res.data;

                    dataLoaded.value = true;
                }
            } catch (error) {
                if (error.response && error.response.status === 403) {
                    permissionError.value =
                        "Access forbidden: You do not have the necessary permissions. to view Others data";
                }
                dataLoaded.value = false;
            }
        };

        watch(searchFilter, (newFilter, oldFilter) => {
            const { date, keywords, sentimentTypes } = newFilter;
            search.value = {
                date: date,
                keywords: keywords,
                sentimentTypes: sentimentTypes,
            };
            dataLoaded.value = false;

            getData();
        });

        onMounted(async () => {
            search.value = {
                date: searchFilter.value.date,
                keywords: searchFilter.value.keywords,
                sentimentTypes: searchFilter.value.sentimentTypes,
            };

            await getData();
        });

        return {
            markers,
            markerStyle,
            dataLoaded,
            searchFilter,
            LoadingGif,
            search,
            permissionError,
        };
    },
});
</script>
