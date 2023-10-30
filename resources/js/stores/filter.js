import { defineStore } from "pinia";

export const useFilterStore = defineStore("filter", {
    state: () => ({
        date: null,
        keywords: null,
        sentimentTypes: null,
    }),
    getters: {
        searchFilter: (state) => ({
            date: state.date,
            keywords: state.keywords,
            sentimentTypes: state.sentimentTypes,
        }),
    },
    actions: {
        // ...
    },
    persist: true,
});
