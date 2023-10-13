<template>
    <div class="side-modal" :class="{ 'showing-modal': showing }">
        <div class="dialog col-lg-7 col-10" role="document">
            <button
                type="button"
                class="close close-btn"
                data-dismiss="modal"
                @click="$emit('hideModal')"
            >
                <span aria-hidden="true">&times;</span>
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
    overflow: auto;
    transform: translateX(100%);
    transition: transform 0.3s ease-in-out;

    &:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        z-index: 5;
        background-color: rgba(244, 245, 247, 0.6);
        inset: 0px;
        backdrop-filter: blur(2px);
        width: 100%;
        height: 100vh;
        opacity: 0;
        transition-delay: 300ms;
        transition: opacity 0.3s ease-in-out;
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
        border-top-left-radius: 1.25em;
        box-shadow: -2px -2px 3px #b6b5b5;
        position: absolute;
        right: 0;
        padding: 0px !important;
        z-index: 15;
        z-index: 999;
        background-color: #fff;

        h3 {
            color: #7f9297;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #7f9297;
            padding-bottom: 5px;
            margin-top: 0;
            margin-bottom: 35px;
        }

        .permission-box {
            display: flex;
        }

        .owner-rapper {
            .owner-info-wrapper {
                position: absolute;
                left: 51%;
                width: 48%;
            }

            .remove-owner-wrapper {
                width: 0.5em;
                border: none;

                .remove-owner {
                    border-radius: 1.5rem;
                    box-shadow: 0px -1px 3px #070707;
                    cursor: pointer;
                }
            }
        }

        .form-group {
            margin-bottom: 15px;
            // NEST
            .form-control {
            }
        }

        input.search-owners {
            padding: 10px 15px;
        }

        .users-list-wrap {
            border: 2px solid #707070;
            border-top: none;
            max-height: 300px;
            overflow-y: auto;
            padding-left: 15px;
            position: relative;
            // NEST
            li {
                // padding: 10px 15px;
                // NEST
                a {
                    background-color: #fff;
                    padding: 10px 15px;
                }

                &.add-new-list {
                    position: sticky;
                    bottom: 0;
                    left: 0;
                    z-index: 999;
                    width: 100%;

                    a {
                        display: block;
                        font-weight: 600;
                        color: #707070;
                        margin-left: -15px;
                    }
                }
            }
        }
    }

    .new-owner-form {
        margin-top: 25px;
        // NEST
        /* .button-sec{
                padding: 5px 15px;
                background-color: #707070;
            } */
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
</style>
