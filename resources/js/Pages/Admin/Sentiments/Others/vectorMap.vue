<template>
    <!-- <div class="col-12 shadow mx-0">
        Count Of Location by Location and Label
    </div> -->
    <div class="col-12 mx-0 ox-0 shadow-sm py-5">
        <vuevectormap
            v-if="dataLoaded"
            width="100%"
            height="450"
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
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch } from "vue";

export default defineComponent({
    name: "sentiment-analysis-trends-vectorMap",

    components: {},
    props: ["filter"],

    setup(props) {
        const searchFilter = ref({
            date: "",
            keywords: "",
        });
        const markers = ref([]);
        const dataLoaded = ref(false);
        const markerStyle = ref({
            initial: {
                fontFamily: "'Inter', sans-serif",
                fontSize: 1,
                border: "none",
                fontWeight: 200,
                fill: "#35373e",
                strokeWidth: 1,
                r: 5,
            },
            hover: {
                fill: "",
            },
            selected: {
                fill: "blue",
            },
        });

        const getData = async () => {
            try {
                const res = await axios.post(
                    `/admin/sentiments/mapCoorddinates`,
                    { searchFilter: searchFilter.value }
                );
                if (res.status === 200) {
                    // res.data.forEach((item) => {
                    //     markers.value.push(item);
                    // });
                    markers.value = res.data;
                    console.log(markers.value);

                    dataLoaded.value = true;
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };

        watch(
            () => props.filter,
            (first, second) => {
                searchFilter.value = first;
                dataLoaded.value = false;

                getData();
            }
        );

        onMounted(async () => {
            getData();
        });

        return {
            markers,
            markerStyle,
            dataLoaded,
            searchFilter,
        };
    },
});
</script>
