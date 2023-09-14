import { defineStore } from "pinia";

export const useFilterStore = defineStore("filter", {
    state: () => ({
        date: null,
        keywords: null,
    }),
    getters: {
        searchFilter: (state) => ({
            date: state.date,
            keywords: state.keywords,
        }),
    },
    actions: {
        // ...
    },
});
