<template>
    <div class="col-12 mx-0 px-0">
        <div class="row pt-1 px-0" v-if="temp">
            <div
                class="col-2 col-lg-4 px-0 mx-0 pt-lg-4 pt-3 d-flex justify-content-start"
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
                    <!-- <div class="col-lg-7 col-2 mx-0 px-0">
                        <div class="row mx-0 px-0">
                            <div class="col-12 px-0 mx-0 pt-2">
                                <div class="degree rounded-circle"></div>
                            </div>
                            <div
                                class="col-12 mx-0 px-0 pt-lg-3 pt-2 description"
                            >
                                {{ description }}
                            </div>
                            <div class="col-12 fs-6 pt-2">Today {{ date }}</div>
                            <div class="col-12 fs-5">{{ city }}</div>
                        </div>
                    </div> -->
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

@media only screen and (max-width: 991px) {
    .colored-icon {
        filter: brightness(100%) contrast(120%);
        width: 60px;
        height: 60px;
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
