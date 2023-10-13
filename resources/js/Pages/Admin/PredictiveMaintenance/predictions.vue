<template>
    <div class="col-12 px-0 mx-0 predictions-table-tree">
        <div class="row medium-labels">
            <table class="table">
                <thead>
                    <tr>
                        <th width="10%" class="text-center">#</th>
                        <th width="15%" class="text-center">OC</th>
                        <th width="10%" class="text-center">Classification</th>
                        <th width="15%" class="text-end">Site Name</th>
                        <th width="10%" class="text-end">Device Name</th>
                        <th width="15%" class="text-start">Sensor Info</th>
                        <th width="10%" class="text-start">Date</th>
                        <th width="5%%" class="text-start">Day</th>
                        <th width="10%" class="text-center">state</th>
                        <th width="10%" class="text-center">% in Alarm</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="col-12 pt-5 desktop-labels">
            <div class="d-flex flex-row">
                <div class="dh">#</div>

                <div class="dh" style="text-align: center">OC</div>
                <div class="dh">Class</div>
                <div class="dh">Site Name</div>
                <div class="dh">Device Name</div>
                <div class="dh">Sensor Info</div>
                <div class="dh" style="width: 100px !important">Date</div>
                <div class="dh">Day</div>
                <div class="dh">State</div>
                <div class="dh">% in Alarm</div>
            </div>
        </div>
        <div class="py-0">
            <blocks-tree
                :data="treeData"
                horizontal="true"
                :collapsable="true"
            ></blocks-tree>
        </div>
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
        const treeData = ref({
            label: "Prediction",
            expand: true,
            children: [],
        });

        const transformPredictionsToTree = () => {
            props.predictions.forEach((prediction) => {
                // Extract relevant data from prediction
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

                // Create the hierarchy in treeData
                let ocNode = findOrCreateNode(treeData.value.children, OC);

                ocNode.expand = true;

                let classificationNode = findOrCreateNode(
                    ocNode.children,
                    Classification_x
                );

                classificationNode.expand = true;

                let siteNode = findOrCreateNode(
                    classificationNode.children,
                    SiteName
                );

                let deviceNode = findOrCreateNode(
                    siteNode.children,
                    DeviceName
                );
                let sensorNode = findOrCreateNode(deviceNode.children, item_id);

                const dateObj = new Date(date);

                const dayNames = [
                    "Sunday",
                    "Monday",
                    "Tuesday",
                    "Wednesday",
                    "Thursday",
                    "Friday",
                    "Saturday",
                ];

                const dayName = dayNames[dateObj.getDay()];

                let dateNode = findOrCreateNode(sensorNode.children, date);
                let dayNode = findOrCreateNode(dateNode.children, dayName);
                let stateNode = findOrCreateNode(dayNode.children, alarm);

                const targetValueNumber = parseFloat(target_value);

                // Check if targetValueNumber is a valid number
                if (!isNaN(targetValueNumber)) {
                    // Add the prediction data as a child of the stateNode
                    stateNode.children.push({
                        label: targetValueNumber.toFixed(),
                        some_id: targetValueNumber.toFixed(),
                        expand: true,
                    });
                }
            });
        };

        // Helper function to find or create a node
        const findOrCreateNode = (nodes, label) => {
            const existingNode = nodes.find((node) => node.label === label);
            if (existingNode) {
                return existingNode;
            } else {
                const newNode = {
                    label,
                    expand: false,
                    children: [],
                };
                nodes.push(newNode);
                return newNode;
            }
        };

        onMounted(() => {
            transformPredictionsToTree();
        });

        return {
            treeData,
        };
    },
});
</script>

<style>
/* .horizontal .org-tree-node-children > .org-tree-node {
    display: table-cell !important;
} */

/* .org-tree-node,
.org-tree-node-children {
    position: inherit !important;
} */

.horizontal .org-tree-node-label {
    font-size: 10px !important;
}

.horizontal .org-tree-node-children {
    display: table-cell;
    padding-top: 0;
}

.org-tree-node-label {
    width: 100px !important;
}

.dh {
    width: 160px !important;
    text-align: left !important;
    font-size: 12px;
    font-weight: bold;
}

.desktop-labels {
    display: none;
}

.medium-labels {
    display: block;
}

/* Show .desktop-labels and hide .medium-labels for screens between 1601px and 3000px */
@media (min-width: 1601px) and (max-width: 3000px) {
    .desktop-labels {
        display: block;
    }

    .medium-labels {
        display: none;
    }
}

.table thead th {
    font-size: 12px !important;
    color: #144f9f;
    font-weight: bold;
}
</style>
