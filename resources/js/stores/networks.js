import { defineStore } from "pinia";

export const useFilterProvince = defineStore("selectdProvince", {
    state: () => ({
        province: "",
    }),
    getters: {
        FilterProvince: (state) => ({
            province: state.province,
        }),
    },
    actions: {
        // ...
    },
    persist: true,
});
