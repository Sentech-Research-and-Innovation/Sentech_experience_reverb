<template>
    <div v-if="temp" class="col-6 bg mx-0">
        <div class="row px-0">
            <div class="col-2 px-0 mx-0 pt-0 d-flex justify-content-start">
                <img :src="weatherIconUrl" class="colored-icon" />
            </div>
            <div class="col-10 pt-0">
                <div class="row">
                    <div class="col-3 d-flex">
                        <div class="temp">
                            {{ temp }}
                        </div>
                        <div class="col-12 px-0 mx-0 pt-2">
                            <div class="degree rounded-circle mx-1"></div>
                            <div class="col-12 mx-0 px-1 pt-2 description">
                                {{ description }}
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="col-12 fs-6 pt-2 mx-0 date text-center">
                            {{ date }}
                        </div>
                        <div class="col-12 fs-5 mx-0 text-center city">
                            {{ city }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="col-12 text-start pt-2">
        <p>Loading...</p>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
export default defineComponent({
    components: {},

    setup() {
        const description = ref("");
        const weatherIconUrl = ref("");
        const city = ref("");
        const temp = ref("");
        const date = ref("");
        const success = async () => {
            const res = await axios.get(`/web/weather`);
            if (res.status === 200) {
                let data = res.data[0];
                const icon = data.weather[0].icon;

                weatherIconUrl.value = `http://openweathermap.org/img/wn/${icon}@2x.png`;
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
.bg {
    color: #144f9f !important;
    border-radius: 25px;
}
.colored-icon {
    filter: brightness(100%) contrast(120%);
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
    border: 2px solid #144f9f;
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
/* . weather-text{
    font-size
} */
</style>
