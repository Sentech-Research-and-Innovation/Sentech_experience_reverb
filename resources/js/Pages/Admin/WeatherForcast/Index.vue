<template>
    <div class="col-12 pl-5">
        <div class="row mt-5">
            <div class="col-3">
                <div class="col-12 px-0" style="color: #737272">
                    {{ data[1].city }}
                </div>
                <div class="col-12 weather-heading pt-0 px-0">Weather</div>
                <div class="col-12 weather-heading px-0 mt-5">
                    <img
                        :src="weatherIconUrl(data[0].weather[0].icon)"
                        width="50"
                        class="colored-icon"
                    />
                </div>
                <div class="col-12 weather-heading-temp px-0">
                    {{ data[0].main.temp.toFixed() }}°
                </div>

                <div class="col-12 weather-heading-des pt-0 px-0">
                    {{ data[0].weather[0].description }}
                </div>
                <div class="date px-0 pt-1">
                    Feels Like {{ data[0].main.feels_like.toFixed() }} C
                </div>
                <div class="col-12 px-0 pt-4">
                    <div class="d-flex">
                        <div class="mr-3">
                            <div>Max Temp</div>
                            <div class="date pt-2">
                                {{ data[0].main.temp_max.toFixed() }} C
                            </div>
                        </div>
                        <div class="">
                            <div>Min Temp</div>
                            <div class="date text-start pt-2">
                                {{ data[0].main.temp_min.toFixed() }} C
                            </div>
                        </div>
                    </div>
                    <div class="d-flex pt-3">
                        <div class="mr-4">
                            <div>Humidity</div>
                            <div class="date pt-2">
                                {{ data[0].main.humidity }} %
                            </div>
                        </div>
                        <div class="">
                            <div>Wind</div>
                            <div class="date text-start pt-2">
                                {{ speedToKM(data[0].wind.speed) }} km/h
                            </div>
                        </div>
                    </div>

                    <div class="d-flex pt-3">
                        <div class="mr-4">
                            <div>Visibility</div>
                            <div class="date pt-2">
                                {{ metersToKilometers(data[0].visibility) }}
                                Km
                            </div>
                        </div>
                        <div class="">
                            <div>Pressure</div>
                            <div class="date text-start pt-2">
                                {{ data[0].main.pressure.toFixed() }} hPa
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9 pr-5 mt-5 pt-5">
                <div class="row pt-4 mt-5" style="background-color: #ffff">
                    <div
                        class="col-2 mx-0 px-0 pb-5 mb-5"
                        style="border-bottom: 10px solid #efefef"
                        v-for="(forecast, index) in data.forecast.list"
                        :key="forecast.dt"
                    >
                        <div
                            class="col-12 px-0"
                            style="border-right: 2px solid #efefef"
                        >
                            <div class="col-12 pt-2 date text-center">
                                {{ formatDate(forecast.dt) }}
                            </div>
                            <div
                                class="col-12 weather-container text-center py-4"
                            >
                                <img
                                    :src="
                                        weatherIconUrl(forecast.weather[0].icon)
                                    "
                                    class="colored-icon"
                                    width="40"
                                />
                            </div>
                            <div class="col-12 temperature text-center">
                                {{ forecast.main.temp_max.toFixed() }} ° /
                                {{ forecast.main.temp_min.toFixed() }}
                                °
                            </div>
                            <div class="col-12 text-center">
                                {{ forecast.weather[0].description }}
                            </div>
                        </div>
                        <!-- Add a new row after every 6 items -->
                        <div v-if="(index + 1) % 6 === 0" class="w-100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import brokenClouds from "../../../assets/weatherIcons/brokenClouds.png";
import clearSky from "../../../assets/weatherIcons/clearSky.png";
import fewClouds from "../../../assets/weatherIcons/fewClouds.png";
import mist from "../../../assets/weatherIcons/mist.png";
import rain from "../../../assets/weatherIcons/rain.png";
import scatteredClouds from "../../../assets/weatherIcons/scatteredClouds.png";
import showerRain from "../../../assets/weatherIcons/showerRain.png";
import snow from "../../../assets/weatherIcons/snow.png";
import thunderstorm from "../../../assets/weatherIcons/thunderstorm.png";

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
            const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            const months = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ];

            const date = new Date(timestamp * 1000);
            const day = days[date.getUTCDay()];
            const month = months[date.getUTCMonth()];
            const dayOfMonth = date.getUTCDate();
            const hours = date.getUTCHours() % 12 || 12;
            const ampm = date.getUTCHours() >= 12 ? "pm" : "am";

            return `${day} ${dayOfMonth}, ${hours + ":00"} ${ampm}`;
        };

        const weatherIcons = {
            "01n": clearSky,
            "01d": clearSky,
            "02n": fewClouds,
            "02d": fewClouds,
            "03n": scatteredClouds,
            "03d": scatteredClouds,
            "04n": brokenClouds,
            "04d": brokenClouds,
            "09n": showerRain,
            "09d": showerRain,
            "10n": rain,
            "10d": rain,
            "11n": thunderstorm,
            "11d": thunderstorm,
            "13n": snow,
            "13d": snow,
            "50n": mist,
            "50d": mist,
        };

        const defaultIconUrl = "http://openweathermap.org/img/wn/";

        const weatherIconUrl = (iconCode) => {
            return (
                weatherIcons[iconCode] || `${defaultIconUrl}${iconCode}@2x.png`
            );
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

.date {
    font-size: 13px;
    font-weight: bold;
}

.temperature {
    font-weight: bold;
    font-size: 16px;
}

.weather-heading {
    font-weight: bold;
    font-size: 25px;
    color: #144f9f;
}

.weather-heading-temp {
    color: #144f9f;
    font-weight: 900;
    font-size: 70px;
}

.weather-heading-des {
    color: #144f9f;
    font-weight: bold;
    font-size: 13px;
}
</style>
