<template>
    <div class="col-12 pt-4 px-5">
        <div class="row mb-5 pb-2">
            <div class="col-3 py-2">
                <div class="col-12 weather-container-main py-2 text-center">
                    <p>{{ data[1].city }}</p>

                    <img
                        :src="weatherIconUrl(data[0].weather[0].icon)"
                        class="colored-icon"
                    />
                    <div class="py-3">{{ data[0].main.temp.toFixed() }} °C</div>

                    <p>{{ data[0].weather[0].description }}</p>
                </div>
            </div>
            <div class="col-3 py-2">
                <div
                    class="col-12 weather-container-main py-2 text-center py-3"
                >
                    <div class="row px-1">
                        <div class="col-6 py-1 text-start">
                            <p>Feeels Like.</p>
                            <p>Humidity.</p>
                            <p>Wind.</p>
                            <p>Visibility.</p>
                            <p>Max Temp.</p>
                            <p>Min Temp.</p>
                            <p>Pressure.</p>
                        </div>
                        <div class="col-6 py-1 weather-details">
                            <p>{{ data[0].main.feels_like.toFixed() }} °C</p>
                            <p>{{ data[0].main.humidity }} %</p>
                            <p>{{ speedToKM(data[0].wind.speed) }} km/h</p>
                            <p>
                                {{ metersToKilometers(data[0].visibility) }}
                                Km
                            </p>
                            <p>{{ data[0].main.temp_max.toFixed() }} °C</p>
                            <p>{{ data[0].main.temp_min.toFixed() }} °C</p>
                            <p>{{ data[0].main.pressure.toFixed() }} hPa</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 py-2 weather-container-map">
                <vuevectormap
                    width="100%"
                    height="240"
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
        <div class="row">
            <div
                class="col-2 py-2 mx-0"
                v-for="forecast in data.forecast.list"
                :key="forecast.dt"
            >
                <div class="col-12 date-container py-2">
                    {{ formatDate(forecast.dt) }}
                </div>
                <div class="col-12 weather-container py-2 text-center">
                    <img
                        :src="weatherIconUrl(forecast.weather[0].icon)"
                        class="colored-icon"
                    />
                    <p>{{ forecast.main.temp.toFixed() }}°C</p>
                    <p>{{ forecast.weather[0].description }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default defineComponent({
    name: "WeatherForecast",
    layout: AdminLayout,

    components: {},

    props: {
        data: {
            type: Object,
            required: true,
        },
    },

    setup(props) {
        const { data } = props;
        const formatDate = (timestamp) => {
            const date = new Date(timestamp * 1000);
            return date.toLocaleString();
        };

        const weatherIconUrl = (icon) => {
            return `http://openweathermap.org/img/wn/${icon}@2x.png`;
        };

        const speedToKM = (speedMS) => {
            let convert = speedMS * 3.6;

            return convert.toFixed();
        };

        const metersToKilometers = (value) => {
            let convert = value / 100;

            return convert.toFixed();
        };
        return {
            data,
            formatDate,
            weatherIconUrl,
            speedToKM,
            metersToKilometers,
        };
    },
});
</script>
<style scoped>
.date-container {
    background-color: #144f9f;
    border-top-right-radius: 10px;
    border-top-left-radius: 10px;
    color: #ffff;
    font-weight: bold;
}
.weather-container {
    background-color: #ffff;
    border-bottom-right-radius: 10px;
    border-bottom-left-radius: 10px;
    color: #144f9f;
}

.weather-container-main {
    background-color: #ffff;
    border-radius: 10px;
    font-size: 35px !important;
    font-weight: bold;

    color: #144f9f;
}

.weather-details {
    background-color: #144f9f;
    border-radius: 10px;
    color: #ffff;
}

.weather-container-map {
    background-color: #ffff;
    border-radius: 10px;
}
</style>
