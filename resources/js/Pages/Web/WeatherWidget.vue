<template>
    <div class="col-12 mx-0 px-0">
        <div class="row pt-1 px-0" v-if="temp">
            <div
                class="col-2 col-lg-4 px-0 mx-0 pt-lg-5 pt-4 d-flex justify-content-center"
            >
                <img :src="weatherIconUrl" class="colored-icon" />
            </div>
            <div class="col-lg-8 col-10 py-4">
                <div class="row">
                    <div class="col-lg-5 col-3 d-flex">
                        <div class="temp">
                            {{ temp }}
                        </div>
                        <div class="col-12 px-0 mx-0 pt-2">
                            <div class="degree rounded-circle mx-1"></div>
                            <div
                                class="col-12 mx-0 px-1 pt-lg-3 pt-2 description"
                            >
                                {{ description }}
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-9">
                        <div class="col-12 fs-6 pt-2 px-4 px-lg-0 mx-0 date">
                            Today {{ date }}
                        </div>
                        <div class="col-12 fs-5 px-4 px-lg-0 mx-0 city">
                            {{ city }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="col-12 text-center pt-5 mt-4">
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
        const description = ref("");
        const weatherIconUrl = ref("");
        const city = ref("");
        const temp = ref("");
        const date = ref("");

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

        const success = async () => {
            const res = await axios.get(`/web/weather`);
            if (res.status === 200) {
                let data = res.data[0];
                const icon = data.weather[0].icon;

                weatherIconUrl.value = weatherIcons[icon];
                description.value = data.weather[0].main;
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

                date.value = day + "," + month;
            }
        };

        onMounted(() => {
            success();
        });

        return {
            weatherIconUrl,
            description,
            date,
            temp,
            city,
        };
    },
});
</script>

<style scoped>
.colored-icon {
    width: 80px;
    height: 80px;
}
.temp {
    font-size: 60px;
    font-weight: bold;
}

.degree {
    width: 20px;
    height: 20px;
    border: 3px solid #ffff;
}

@media only screen and (max-width: 991px) {
    .colored-icon {
        width: 50px;
        height: 50px;
    }

    .temp {
        font-size: 40px;
        font-weight: bold;
    }
    .degree {
        width: 10px;
        height: 10px;
        border: 2px solid #ffff;
    }

    .description {
        font-size: 11px;
    }

    .date {
        font-size: 12px !important;
    }

    .city {
        font-size: 14px !important;
        font-weight: bold;
    }
}
/* . weather-text{
    font-size
} */
</style>
//45aee2ef715cfa91ed7957e8cfd37a70
