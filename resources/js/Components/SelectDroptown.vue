<template>
    <div class="col-12 py-0 mx-0 px-0">
        <div class="checkbox-selects">
            <div
                class="checkbox-select__trigger"
                ref="buttonRef"
                v-click-outside="onClickOutside"
            >
                <span class="checkbox-select__title"
                    >Selected({{ checkedFilters.length }})</span
                >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 129 129">
                    <path
                        d="M121.3 34.6c-1.6-1.6-4.2-1.6-5.8 0l-51 51.1-51.1-51.1c-1.6-1.6-4.2-1.6-5.8 0-1.6 1.6-1.6 4.2 0 5.8l53.9 53.9c.8.8 1.8 1.2 2.9 1.2 1 0 2.1-.4 2.9-1.2l53.9-53.9c1.7-1.6 1.7-4.2.1-5.8z"
                    />
                </svg>
            </div>
            <el-popover
                ref="popoverRef"
                :virtual-ref="buttonRef"
                trigger="click"
                virtual-triggering
                :width="300"
            >
                <div class="">
                    <input
                        type="text"
                        placeholder="Search value"
                        class="search_select px-2"
                        v-model="search"
                    />
                </div>
                <div class="checkbox-select__col">
                    <div class="checkbox-select__select-all">
                        <label for="selectAll">{{ selectAllText }}</label>
                        <input
                            type="checkbox"
                            id="selectAll"
                            @change="toggleSelectAll"
                            :checked="allSelected"
                        />
                    </div>
                    <div class="checkbox-select__info">
                        {{ checkedFilters.length }} SELECTED
                    </div>
                </div>

                <ul
                    id="customScroll"
                    class="checkbox-select__filters-wrapp"
                    data-simplebar-auto-hide="true"
                >
                    <li v-for="(filter, index) in filteredList" :key="index">
                        <div class="checkbox-select__check-wrapp">
                            <input
                                class="conditions-check"
                                :checked="checkedFilters.includes(filter)"
                                @change="toggleSelect(filter)"
                                type="checkbox"
                                :id="'checkbox-' + index"
                            />
                            <label :for="'checkbox-' + index">{{
                                filter
                            }}</label>
                        </div>
                    </li>
                </ul>
            </el-popover>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, unref, computed, onMounted } from "vue";

export default defineComponent({
    components: {},
    props: {
        filters: {
            type: Array,
            required: true,
        },
        options: {
            type: Array,
            required: true,
        },
        modelValue: {
            type: Array,
            default: () => [],
        },
    },
    setup(props, { emit }) {
        const search = ref("");
        const checkedFilters = ref([]);
        const allSelected = ref(false);
        const selectAllText = ref("Select All");

        const showLoader = ref(false);
        const filteredList = computed(() => {
            return props.filters.filter((item) =>
                item.toLowerCase().includes(search.value.toLowerCase())
            );
        });

        const toggleSelectAll = () => {
            if (checkedFilters.value.length === props.filters.length) {
                checkedFilters.value = []; // Clear all filters if all are selected
                selectAllText.value = "Select All";
            } else {
                checkedFilters.value = [...props.filters]; // Select all filters if none or some are selected
                selectAllText.value = "Clear All";
            }

            // Emit the updated value using v-model
            emit("update:modelValue", checkedFilters.value);
        };
        const toggleSelect = (filter) => {
            const index = checkedFilters.value.indexOf(filter);

            // Create a new array to ensure reactivity
            const newFilters = [...checkedFilters.value];

            if (index === -1) {
                newFilters.push(filter);
            } else {
                newFilters.splice(index, 1);
            }

            // Update the checkedFilters with the new array
            checkedFilters.value = newFilters;

            // Emit the updated value using v-model
            emit("update:modelValue", checkedFilters.value);
        };

        onMounted(() => {
            if (props.options) {
                checkedFilters.value = [...props.options];
            }
            emit("update:modelValue", checkedFilters.value);
        });

        const buttonRef = ref();
        const popoverRef = ref();
        const onClickOutside = () => {
            unref(popoverRef).popperRef?.delayHide?.();
        };
        return {
            search,
            checkedFilters,
            allSelected,
            selectAllText,
            showLoader,
            filteredList,
            toggleSelectAll,
            toggleSelect,
            buttonRef,
            onClickOutside,
        };
    },
});
</script>

<style lang="scss">
.search_select::placeholder {
    color: #144f9f !important;
    opacity: 1;
    font-size: 12px;
    font-weight: 400;
}
.search_select {
    margin-top: 10px !important;
    margin-bottom: 10px !important;
    font-weight: 100 !important;
    height: 30px !important;
    border-radius: 5px;
    border: 1px solid #144f9f;
    color: #144f9f !important;
    width: 100%;
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
        border: 1px solid #144f9f !important;
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
        margin-top: 10px;
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
