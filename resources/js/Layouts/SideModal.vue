<template>
    <div class="side-modal" :class="{ 'showing-modal': showing }">
        <div class="dialog col-lg-7 col-10 shadow" role="document">
            <button
                type="button"
                class="close close-btn"
                data-dismiss="modal"
                @click="$emit('hideModal')"
            >
                <span aria-hidden="true" class="close-btn-content"
                    >&times;</span
                >
            </button>
            <h1 class="mt-5 px-4">{{ content.create.title }}</h1>
            <div class="content">
                <div class="body">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["content", "showing"],
    methods() {},
};
</script>

<style scoped lang="scss">
.side-modal {
    height: 100vh;
    position: fixed;
    top: 0;
    right: 0;
    z-index: 999;
    width: 100%;
    padding-top: 0px;
    overflow: hidden;
    transform: translateX(100%);
    transition: transform 0s;
    &:before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        background-color: rgba(244, 245, 247, 0.8);
        inset: 0px;
        backdrop-filter: blur(0px);
        width: 100%;
        height: 100vh;
        opacity: 0;

        //     transition-delay: 300ms;
        //     transition: opacity 0.3s ease-in-out;
    }

    &.showing-modal {
        transform: translateX(0);

        &:before {
            opacity: 1;
        }
    }

    .dialog {
        border: none;
        width: 100%;
        min-height: 100vh;
        border-top-left-radius: 10px;
        position: absolute;
        right: 0;
        padding: 0px !important;
        z-index: 15;
        margin-top: 10px;
        z-index: 1000;
        background-color: #fff;
    }

    .close-btn {
        position: absolute;
        top: 0;
        left: -54px;
        z-index: 999;
        background: #fff;
        border-radius: 50px;
        border: none;
        font-size: 26px;
        box-shadow: -2px 0px 8px #e6dcdc;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
}
body {
    overflow: hidden; /* Hide scrollbars */
}
</style>
<style>
body {
    overflow: hidden; /* Hide scrollbars */
}
.close-btn-content:hover {
    rotate: 2turn;
}
</style>
