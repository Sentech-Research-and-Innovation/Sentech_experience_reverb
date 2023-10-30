import { defineStore } from "pinia";

export const predictionsFilterStore = defineStore("filterPredictionsDetailed", {
    state: () => ({
        siteNames: null,
        date: null,
        measureDecription: null,
        deviceName: null,
        classification: null,
        alarmFlag: null,
    }),
    getters: {
        searchFilter: (state) => ({
            siteNames: state.siteNames,
            date: state.date,
            measureDecription: state.measureDecription,
            deviceName: state.deviceName,
            classification: state.classification,
            alarmFlag: state.alarmFlag,
        }),
    },
    actions: {
        // ...
    },
    persist: true,
});
