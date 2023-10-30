<template>
    <div class="custom-tree-container col-12 py-5 shadow-border">
        <div class="col-12 fs-5 pb-2">Predictions</div>
        <el-tree :data="dataSource" node-key="id" :expand-on-click-node="true">
            <template #default="{ node, data }">
                <span class="custom-tree-node">
                    <span>{{ node.label }}</span>

                    <span>
                        <a
                            @click="append(data)"
                            style="font-size: 13px; color: #409eff"
                        >
                            {{ data.tag }}</a
                        >
                        <a
                            style="
                                margin-left: 40px;
                                font-size: 13px;
                                color: #409eff;
                            "
                            @click="remove(node, data)"
                        >
                            100
                        </a>
                    </span>
                </span>
            </template>
        </el-tree>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";

export default defineComponent({
    props: {
        predictions: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const id = ref(1000);
        const dataSource = ref([]);

        const findOrCreateNode = (nodes, label, tag) => {
            let node = nodes.find((n) => n.label === label);
            if (!node) {
                node = {
                    id: id.value++,
                    label: label,
                    tag: tag,
                    children: [],
                };
                nodes.push(node);
            }
            return node;
        };

        const transformData = () => {
            props.predictions.forEach((prediction) => {
                const {
                    OC,
                    Classification_x,
                    SiteName,
                    DeviceName,
                    item_id,
                    date,
                    alarm,
                    target_value,
                } = prediction;

                let ocNode = findOrCreateNode(dataSource.value, OC, "OC");

                let classificationNode = findOrCreateNode(
                    ocNode.children,
                    Classification_x,
                    "Classification"
                );
                classificationNode.expand = true;

                let siteNode = findOrCreateNode(
                    classificationNode.children,
                    SiteName,
                    "Site Name"
                );

                let deviceNode = findOrCreateNode(
                    siteNode.children,
                    DeviceName,
                    "Device Name"
                );

                let sensorNode = findOrCreateNode(
                    deviceNode.children,
                    item_id,
                    "Sensor Id"
                );

                let dateNode = findOrCreateNode(
                    sensorNode.children,
                    date,
                    "Date"
                );

                let alarmNode = findOrCreateNode(
                    dateNode.children,
                    alarm,
                    "% in Alarm"
                );

                const targetValueNumber = parseFloat(target_value);
                if (!isNaN(targetValueNumber)) {
                    alarmNode.children.push({
                        id: id.value++,
                        label: targetValueNumber.toFixed(),
                    });
                }
            });
        };

        const defaultProps = {
            children: "children",
            label: "label",
        };

        onMounted(() => {
            transformData();
        });

        const append = (data) => {
            // Implement your append logic here
        };

        const remove = (node, data) => {
            // Implement your remove logic here
        };

        return {
            dataSource,
            append,
            remove,
            defaultProps,
        };
    },
});
</script>

<style scoped>
.custom-tree-node {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
    padding-right: 8px;
}

.el-tree-node__content {
    background-color: red !important;
}
</style>
