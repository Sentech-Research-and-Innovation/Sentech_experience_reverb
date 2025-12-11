// import { defineStore } from "pinia";

// export const useFilterStore = defineStore("filter", {
//     state: () => ({
//         date: null,
//         keywords: null,
//         sentimentTypes: null,
//     }),
//     getters: {
//         searchFilter: (state) => ({
//             date: state.date,
//             keywords: state.keywords,
//             sentimentTypes: state.sentimentTypes,
//         }),
//     },
//     actions: {
//         // ...
//     },
//     persist: true,
// });

import { defineStore } from "pinia";

export const useFilterStore = defineStore("filter", {
    state: () => {
        const today = new Date();
        const lastYear = new Date();
        lastYear.setFullYear(today.getFullYear() - 1);

        return {
            date: [
                lastYear.toISOString().split("T")[0], // startDate (12 months ago)
                today.toISOString().split("T")[0],    // endDate (today)
            ],
            keywords: null,
            sentimentTypes: null,
        };
    },
    getters: {
        searchFilter: (state) => ({
            date: state.date,
            keywords: state.keywords,
            sentimentTypes: state.sentimentTypes,
        }),
    },
    persist: true,
});
