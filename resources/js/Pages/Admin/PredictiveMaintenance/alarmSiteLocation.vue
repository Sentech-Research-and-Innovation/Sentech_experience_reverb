<template>
    <div class="mx-3 py-4 shadow-border my-4">
        <div class="col-12 mx-0">
            <h4>Sensors in alarms by SIte Location</h4>
            <p>
                Showing top 3 in Longitude (#), Latitude (#) and top 3 in
                Sitename
            </p>
        </div>
        <div class="col-12 mt-4 mx-0 px-0">
            <vuevectormap
                width="100%"
                backgroundColor="#0000"
                :options="{
                    // markers,
                    //  markerStyle,
                    // Map options..
                    // markers: []
                    // markerStyle: {}
                    // etc..
                }"
            >
            </vuevectormap>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";

export default defineComponent({
    components: {},
    props: {
        predictions: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const { predictions } = props;
        return {
            predictions,
        };
    },
});
</script>

<style></style>
