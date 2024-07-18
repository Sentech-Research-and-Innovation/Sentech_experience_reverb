<template>
    <div>
        <el-popconfirm
            confirm-button-text="Yes"
            cancel-button-text="No"
            :icon="InfoFilled"
            icon-color="#626AEF"
            title="Are you sure to approve this?"
            @confirm="deleteUser"
            @cancel="cancelEvent"
        >
            <template #reference>
                <el-button type="danger" :icon="DeleteFilled" plain circle />
            </template>
        </el-popconfirm>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";
import { DeleteFilled, InfoFilled } from "@element-plus/icons-vue";

export default defineComponent({
    name: "delete-user",
    props: {
        userId: {
            type: Number,
            required: true,
        },
    },

    components: {},

    setup(props) {
        const { userId } = props;
        const deleteUser = async () => {
            const res = await axios.post(`/admin/user/delete/${userId}`);
            if (res.status === 200) {
                location.reload();
            }
        };

        return {
            DeleteFilled,
            deleteUser,
            InfoFilled,
        };
    },
});
</script>

<style></style>
