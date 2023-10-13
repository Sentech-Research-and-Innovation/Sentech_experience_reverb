<template>
    <div class="col-12 py-4 mx-0">
        <div class="row">
            <div class="col-6"><h4>Prediction Data - Detailed View</h4></div>
            <div class="col-6 text-end d-flex justify-content-end">
                <button
                    type="button"
                    style="color: #144f9f"
                    class="btn col-4 checkbox-select__trigger py-3"
                    @click="isOpen = !isOpen"
                >
                    Filter Columns
                    <svg
                        viewBox="0 0 24 24"
                        width="20"
                        height="20"
                        stroke="currentColor"
                        stroke-width="2"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="transition"
                        :class="{ 'rotate-180': isOpen }"
                    >
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <div
                    v-if="isOpen"
                    class="col-4 px-4 mx-0 border shadow-sm mt-5"
                    style="
                        background-color: #ffff;
                        z-index: 1000;

                        position: absolute;
                    "
                >
                    <ul class="checkbox-select__filters-wrapp">
                        <li v-for="col in cols" :key="col.field">
                            <div
                                class="checkbox-select__check-wrapp text-start"
                            >
                                <label class="form-check-label px-2">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        :checked="!col.hide"
                                        @change="
                                            col.hide = !$event.target.checked
                                        "
                                    />
                                    {{ col.title }}
                                </label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <vue3-datatable
        :rows="rows"
        :columns="cols"
        :stickyFirstColumn="true"
        :stickyHeader="false"
    >
    </vue3-datatable>
</template>

<script>
import { defineComponent, ref, reactive, onMounted, computed } from "vue";
import Vue3Datatable from "@bhplugin/vue3-datatable";
import "@bhplugin/vue3-datatable/dist/style.css";
export default defineComponent({
    components: { Vue3Datatable },
    setup() {
        onMounted(() => {
            getUsers();
        });
        const loading = ref(true);
        const total_rows = ref(0);

        const isOpen = ref(false);

        //  const params = reactive({ current_page: 1, pagesize: 10 });
        const rows = ref(null);

        const cols =
            ref([
                { field: "SiteName", title: "Site Name" },
                { field: "Classification_x", title: "Class" },
                { field: "DeviceName", title: "Device Name" },
                { field: "item_id", title: "Sensor ID" },
                { field: "date", title: "Date" },
                { field: "date", title: "Day" },
                { field: "alarm", title: "In Alarm Flag" },
                { field: "target_value", title: "Predicted Value" },
                { field: "lowerPreAlarmTsh", title: "lowerPreTsh" },
                { field: "lowerPreAlarmTsh", title: "lowerTsh" },
                { field: "upperPreAlarmTsh", title: "upperPreTsh" },
                { field: "upperAlarmTsh", title: "upperTsh" },
            ]) || [];

        const getUsers = async () => {
            try {
                loading.value = true;

                const response = await axios.post(
                    "/admin/predictive-maintenance/predictions/detailed-view-data"
                );

                if (response.status === 200) {
                    rows.value = response.data;
                }

                // rows.value = data?.data;
                // console.log(response);
                // total_rows.value = data?.meta?.total;
            } catch {}

            loading.value = false;
        };

        const changeServer = (data) => {
            params.current_page = data.current_page;
            params.pagesize = data.pagesize;

            getUsers();
        };

        return {
            cols,
            changeServer,
            rows,
            isOpen,
        };
    },
});
</script>

<style lang="scss">
.bh-table-striped {
    font-size: 12px !important;
    color: rgb(0, 79, 159) !important;
}

::placeholder {
    color: rgb(134, 171, 209) !important;
    opacity: 1;
    font-size: 12px;
}
.search_select {
    margin-top: 10px !important;
    margin-bottom: 10px !important;
    font-weight: 100 !important;
    height: 30px !important;
    border-radius: 5px;
    border: 1px solid rgb(134, 171, 209) !important;
    color: rgb(134, 171, 209) !important;
}
input {
    appearance: none;
    border-radius: 0;
}

.checkbox-select {
    position: relative;
    max-width: 440px;
    width: 100%;

    &__trigger {
        border-radius: 5px;
        background: #fff repeat;
        position: relative;
        border: 1px solid rgb(134, 171, 209) !important;
        height: 30px;
        display: flex;
        align-items: center;
        cursor: pointer;
        padding: 0 10px;

        svg {
            width: 16px;
        }
    }
    &__title {
        font-size: 13px;
        flex: 1;
        padding-right: 25px;
        letter-spacing: 1px;
        @media only screen and (max-width: 600px) {
            font-size: 19px;
        }
    }
    &__dropdown {
        opacity: 0;
        visibility: hidden;
        background: #fff;
        position: absolute;
        left: 0;
        right: 0;
        box-shadow: 0 12px 15px 6px rgba(0, 0, 0, 0.1);
        border-radius: 0 0 8px 8px;
        overflow: hidden;
        padding-bottom: 25px;
        &:after,
        &:before {
            position: absolute;
            content: "";
            top: 0;
            display: block;
            height: 4px;
            z-index: 1;
        }
        &:after {
            opacity: 0;
            background: #000;
            left: -200px;
            width: 200px;
            background-color: #2980b9;
            transition: opacity 0.3s ease;
            animation: load 1.8s linear infinite;
            background: linear-gradient(
                135deg,
                rgba(143, 36, 237, 1) 20%,
                rgba(143, 36, 237, 1) 20%,
                rgba(143, 36, 237, 1) 22%,
                rgba(143, 36, 237, 1) 25%,
                rgba(16, 124, 179, 1) 100%
            );
        }
        &:before {
            width: 100%;
            background-color: #000;
        }
        &.activeSearch {
            &:after {
                opacity: 1;
            }
        }
        .simplebar-scrollbar {
            width: 3px;
            right: 1px;
        }
    }
    &__search-wrapp {
        padding: 10px 25px 5px;
        @media only screen and (max-width: 600px) {
            padding: 10px 15px 5px;
        }
        input {
            width: 100%;
            height: 40px;
            border-width: 0 0 2px;
            border-style: solid;
            border-color: #144f9f;
            font-size: 16px;
            background: transparent;
        }
        ::-webkit-input-placeholder {
            /* Chrome/Opera/Safari */
            color: #b8b8b8;
            opacity: 1;
        }
        ::-moz-placeholder {
            /* Firefox 19+ */
            color: #b8b8b8;
            opacity: 1;
        }
        :-ms-input-placeholder {
            /* IE 10+ */
            color: #b8b8b8;
            opacity: 1;
        }
        :-moz-placeholder {
            /* Firefox 18- */
            color: #b8b8b8;
            opacity: 1;
        }
    }
    &__col {
        display: flex;
        font-size: 12px;
        padding: 0 5px;
        justify-content: space-between;
        text-transform: uppercase;
    }
    &__select-all {
        label {
            cursor: pointer;
        }
    }
    &__filters-wrapp {
        margin-top: 20px;
        height: 157px;
        overflow-y: auto;
    }

    &__check-wrapp {
        position: relative;
        padding: 0 0px;
        margin-bottom: 5px;

        input[type="checkbox"] {
            display: block;

            & + label {
                position: relative;
                cursor: pointer;
                font-size: 13px;
                line-height: 22px;
                padding-left: 30px;
                display: inline-block;

                &:after {
                    border: solid 2px #144f9f;
                    content: "";
                    width: 22px;
                    height: 22px;
                    top: 0;
                    left: 0;
                    position: absolute;
                }
                &:before {
                    width: 14px;
                    height: 14px;
                    content: "";
                    position: absolute;
                    top: 4px;
                    left: 4px;
                    background-color: #144f9f;
                    opacity: 0;
                    will-change: transform;
                    transform: scale(0.5);
                    transition: all 0.2s ease;
                }
                &:hover {
                    padding-left: 32px;
                }
            }
            &:checked {
                & + label {
                    &:before {
                        opacity: 1;
                        transform: scale(1);
                    }
                }
            }
        }
    }
}

@keyframes load {
    0% {
        left: -200px;
        width: 20%;
    }
    50% {
        width: 40%;
    }
    70% {
        width: 60%;
    }
    80% {
        left: 50%;
    }
    95% {
        left: 120%;
    }
    100% {
        left: 100%;
    }
}

.link {
    position: absolute;
    left: 0;
    bottom: 0;
    padding: 20px;
    z-index: 9999;
    a {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #fff;
    }
    .fa {
        font-size: 28px;
        margin-right: 8px;
        color: #fff;
    }
}

.hideUser {
    display: inline !important;
    z-index: 1000;

    position: absolute;
    width: 300px !important;
}
</style>
