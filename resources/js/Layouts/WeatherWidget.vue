<template>
    <div class="col-11">
        <div v-if="temp" class="col-lg-12 bg px-0 pb-1">
            <div class="d-flex justify-content-center">
                <div class="col-2 pr-0 mx-0 pt-3 pl-2">
                    <img :src="weatherIconUrl" width="15" height="15" />
                </div>
                <div class="col-10 pt-0 pl-lg-1 pr-0">
                    <div class="row">
                        <div class="col-3 d-flex pl-2 pt-1">
                            <div class="temp">{{ temp }}</div>
                            <div class="col-12 px-0 mx-0 pt-2">
                                <div class="degree rounded-circle mx-1"></div>
                                <div
                                    class="col-12 mx-0 px-1 pt-0 descriptionWeather"
                                >
                                    {{ descriptionWeather }}
                                </div>
                            </div>
                        </div>
                        <div class="col-9 pl-lg-4 pr-0 pt-1">
                            <div
                                class="col-12 fs-6 pt-2 mx-0 date text-left"
                                style="color: #ffff !important"
                            >
                                {{ date }}
                            </div>

                            <h6
                                class="col-12 pt-0 mx-0 city text-left"
                                style="color: #ffff !important"
                            >
                                <span class="city"> {{ city }} </span>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="col-12 text-start pt-0 mx-0">
            <p>Loading...</p>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";

import brokenClouds from "./../assets/weatherIconsWhite/brokenClouds.png";
import clearSky from "./../assets/weatherIconsWhite/clearSky.png";
import fewClouds from "./../assets/weatherIconsWhite/fewClouds.png";
import mist from "./../assets/weatherIconsWhite/mist.png";
import rain from "./../assets/weatherIconsWhite/rain.png";
import scatteredClouds from "./../assets/weatherIconsWhite/scatteredClouds.png";
import showerRain from "./../assets/weatherIconsWhite/showerRain.png";
import snow from "./../assets/weatherIconsWhite/snow.png";
import thunderstorm from "./../assets/weatherIconsWhite/thunderstorm.png";

export default defineComponent({
    components: {},

    setup() {
        const descriptionWeather = ref("");
        const weatherIconUrl = ref("");
        const city = ref("");
        const temp = ref("");

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

        const date = ref("");
        const success = async () => {
            const res = await axios.get(`/web/weather`);
            if (res.status === 200) {
                let data = res.data[0];
                const icon = data.weather[0].icon;

                weatherIconUrl.value = weatherIcons[icon];
                descriptionWeather.value = data.weather[0].main;
                city.value = res.data[1].city;
                temp.value = data.main.temp.toFixed();
                const monthNames = [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December",
                ];
                let objectDate = new Date();
                let day = objectDate.getDate();

                let month = monthNames[objectDate.getMonth()];

                date.value = day + " " + month;
            }
        };

        onMounted(() => {
            success();
        });

        return {
            weatherIconUrl,
            descriptionWeather,
            date,
            temp,
            city,
        };
    },
});
</script>

<style>
.bg {
    background-color: #144f9f !important;
    border-radius: 16px;
    color: #fff;
    text-decoration: none !important;
}

.bg:hover {
    background-color: black !important;
    text-decoration: none;
}
.colored-icon {
    filter: brightness(100%) contrast(120%);
    width: 20px;
    height: 20px;
}

.temp {
    font-size: 29px;
    font-weight: bold;
}
.degree {
    width: 10px;
    height: 10px;
    border: 3px solid #fff;
    margin-top: 4px;
    margin-bottom: 1px;
}

.descriptionWeather {
    font-size: 11px;
    color: #ffff;
}

.date {
    font-size: 12px !important;
}

.city {
    font-size: 10px !important;
    font-weight: 700;
}
/* . weather-text{
    font-size
} */
</style>
