import { computed } from "vue";

export function useGroupedData(permissions = []) {
    const groupedData = computed(() => {
        if (!permissions.value || !Array.isArray(permissions.value)) {
            return {}; // Return an empty object if permissions is null or not an array
        }

        const groupedPermission = {};
        for (const permission of permissions.value) {
            if (!groupedPermission[permission.groupName]) {
                groupedPermission[permission.groupName] = [];
            }
            groupedPermission[permission.groupName].push(permission);
        }
        return groupedPermission;
    });

    return {
        groupedData,
    };
}
