import { defineStore } from "pinia";

export const predictionsFilterStore = defineStore("filterPredictions", {
    state: () => ({
        siteNames: null,
        date: null,
    }),
    getters: {
        searchFilter: (state) => ({
            siteNames: state.siteNames,
            date: state.date,
        }),
    },
    actions: {
        // ...
    },
    persist: true,
});
