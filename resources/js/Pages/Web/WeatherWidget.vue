<template>
    <div class="col-12 mr-5 pr-4">
        <div v-if="temp" class="col-12 bg-1 px-3 pt-2 pb-3">
            <div class="d-flex justify-content-between">
                <div class="col-3 pr-0 mx-0 pt-3 pl-5">
                    <img
                        :src="weatherIconUrl"
                        width="40"
                        height="40"
                        style="object-fit: contain"
                    />
                </div>
                <div class="col-9 pt-0 pl-lg-0 pr-0">
                    <div class="d-flex justify-content-around">
                        <div class="col-5 d-flex pl-0 pt-1">
                            <div class="temp pt-1">{{ temp }}</div>
                            <div class="col-12 px-0 mx-0 pt-2">
                                <div class="degree rounded-circle mx-1"></div>
                                <div
                                    class="col-12 mx-0 px-2 descriptionWeather"
                                >
                                    {{ descriptionWeather }}
                                </div>
                            </div>
                        </div>
                        <div
                            class="col-7 pl-lg-2 pr-0"
                            style="padding-top: 7px"
                        >
                            <div
                                class="col-12 fs-6 pt-2 mx-0 date text-left"
                                style="color: #ffff !important"
                            >
                                Today, {{ date }}
                            </div>

                            <h6
                                class="col-12 pt-1 mx-0 city text-left"
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

import brokenClouds from "../../assets/weatherIconsWhite/brokenClouds.png";

import clearSky from "../../assets/weatherIconsWhite/clearSky.png";

import fewClouds from "../../assets/weatherIconsWhite/fewClouds.png";
import mist from "../../assets/weatherIconsWhite/mist.png";
import rain from "../../assets/weatherIconsWhite/rain.png";
import scatteredClouds from "../../assets/weatherIconsWhite/scatteredClouds.png";
import showerRain from "../../assets/weatherIconsWhite/showerRain.png";
import snow from "../../assets/weatherIconsWhite/snow.png";
import thunderstorm from "../../assets/weatherIconsWhite/thunderstorm.png";

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
.bg-1 {
    background-color: #0c368a !important;
    border-radius: 16px;
    color: #fff;
    text-decoration: none !important;
}

.temp {
    font-size: 45px;
    font-weight: 700;
}
.degree {
    width: 12px;
    height: 12px;
    border: 2px solid #fff;
    margin-top: 4px;
    margin-bottom: 1px;
}

.descriptionWeather {
    font-size: 14px;
    padding-top: 10px;
    color: #ffff;
    padding-left: 10px;
}

.date {
    font-size: 15px !important;
}

.city {
    font-size: 17px !important;
    font-weight: 500;
}
/* . weather-text{
    font-size
} */
</style>
