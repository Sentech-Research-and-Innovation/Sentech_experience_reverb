<template>
    <div class="col-12 mx-0 px-0">
        <div class="row pt-1 px-0" v-if="temp">
            <div class="col-4 px-0 mx-0 pt-4 d-flex justify-content-start">
                <img :src="weatherIconUrl" class="colored-icon" />
            </div>
            <div class="col-8 pt-4">
                <div class="row">
                    <div class="col-5">
                        <div class="temp">
                            {{ temp }}
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="col-12 px-0 mx-0 pt-2">
                            <div class="degree rounded-circle"></div>
                        </div>
                        <div class="col-12 mx-0 px-0 pt-3 fs-6">
                            {{ description }}
                        </div>
                    </div>
                    <div class="col-12 fs-6 pt-2">Today {{ date }}</div>
                    <div class="col-12 fs-5">{{ city }}</div>
                </div>
            </div>
        </div>
        <div v-else class="col-12 text-center pt-5 mt-4">
            <p>Allow location to see weather</p>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref } from "vue";
export default defineComponent({
    components: {},

    setup() {
        const latitude = ref("");
        const longitude = ref("");
        const description = ref("");
        const weatherIconUrl = ref("");
        const city = ref("");
        const temp = ref("");
        const date = ref("");
        const success = async (position) => {
            latitude.value = await position.coords.latitude;
            longitude.value = await position.coords.longitude;
            const res = await axios.post(`/web/weather`, {
                lon: longitude.value,
                lat: latitude.value,
            });
            if (res.status === 200) {
                let data = res.data;
                const icon = data.weather[0].icon;

                weatherIconUrl.value = `http://openweathermap.org/img/wn/${icon}@2x.png`;
                description.value = data.weather[0].description;
                city.value = data.name;
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
        navigator.geolocation.getCurrentPosition(success);
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

<style>
.colored-icon {
    filter: brightness(100%) contrast(120%);
    width: 130px;
    height: 130px;
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

/* . weather-text{
    font-size
} */
</style>
//45aee2ef715cfa91ed7957e8cfd37a70
