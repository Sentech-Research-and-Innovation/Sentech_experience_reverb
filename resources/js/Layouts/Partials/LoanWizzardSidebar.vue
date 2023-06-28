<template>
    <aside id="sidebarMenu" class="lw-sidebar">
        <div class="cm-logo">
            <img src="/images/credit-mate-logo.svg" alt="CreditMate Logo">
        </div>

        <div class="dashboard-main">
            <h4>Loan Application</h4>
            <div class="dash-role" :class="disclaimerStatus">
                Application Start
                <i class="fa-solid fa-check" v-if="step !==''"></i>
            </div>
        </div>

        <div class="sidebar-links">
            <div v-for="(menu, index) in loanApplicationSteps" :key="index"
                 @click="updateStep(index,  menu.progressPerc, menu.title)">
                <a class="nav-link " href="#">
                    <span class="lw-stepper-label"> Step {{ (index + 1) }}   </span>
                    <span class="lw-app-step">{{ menu.title }}</span>
                    <span class="lw-status-icon"
                          :class="{'lw-locked': (menu.status === 'locked'), 'lw-unlocked': (menu.status === 'unlocked'), 'lw-done': (menu.status === 'done')}">
                    <i class="fa-solid " :class="{'fa-lock': (menu.status === 'locked'), 'fa-lock-open': (menu.status === 'unlocked'), 'fa-check': (menu.status === 'done')}"></i>
                </span>
                </a>
            </div>


        </div>

    </aside>

</template>

<script>
    import {Link} from '@inertiajs/vue3';
    import arrow from '../../../../public/svg/arrow.svg';

    export default {
        props: ['step','data'],
        components: {Link, arrow},
        data: function () {
            return {
                payload: {

                },
                lastUnlocked: false,
                loanApplicationSteps: [
                    {
                        title: 'Personal information',
                        status: 'locked',
                        progressPerc: 10,
                    },
                    {
                        title: 'Employment details',
                        status: 'locked',
                        progressPerc: 15,
                    },
                    {
                        title: 'Additional data for referral',
                        status: 'locked',
                        progressPerc: 20,
                    },
                    {
                        title: 'Administration Orders',
                        status: 'locked',
                        progressPerc: 25
                    },
                    {
                        title: 'Debit review orders',
                        status: 'locked',
                        progressPerc: 35,
                    },
                    {
                        title: 'Income',
                        status: 'locked',
                        progressPerc: 40,
                    },
                    {
                        title: 'Trace Alerts',
                        status: 'locked',
                        progressPerc: 50,
                    },
                    {
                        title: 'Recent credit checks',
                        status: 'locked',
                        progressPerc: 60,
                    },
                    {
                        title: 'Adverse Accounts',
                        status: 'locked',
                        progressPerc: 75,
                    },
                    {
                        title: 'Judgements',
                        status: 'locked',
                        progressPerc: 89,
                    },
                    {
                        title: 'NLR | CPA',
                        status: 'locked',
                        progressPerc: 95,
                    },
                    {
                        title: 'Bank statement confirmation',
                        status: 'locked',
                        progressPerc: 100,
                    }
                ],
                disclaimerStatus: 'lw-in-progress-bg'
            }
        },
        watch: {
            'step.currentStep': function (newValue, oldValue) {
                let value = this.step.currentStep;
                let progress = this.step.progress;
                let title = this.step.title;
                if(this.step.currentStep!==false){

                    if (this.step.progress === 10) {
                        let stepData = {
                            currentStep: value,
                            title: title,
                            progress: progress,
                        };
                        this.disclaimerStatus = 'lw-completed-bg';
                        this.$emit('updateStepper', stepData);
                        if (this.lastUnlocked !== false) {
                            if (this.lastUnlocked > 0) {
                                let stepData = {
                                    currentStep: value,
                                    title: title,
                                    progress: progress,
                                };
                                this.disclaimerStatus = 'lw-completed-bg';
                                this.$emit('updateStepper', stepData);
                            }
                        } else {
                            this.lastUnlocked = value + 1;
                        }
                        this.loanApplicationSteps[0].status = 'unlocked';
                    }


                    if (this.step.progress > 10) {


                        if (this.step.currentStep + 1 === value) {

                            if (this.lastUnlocked < value + 1) {
                                this.lastUnlocked = value + 1;
                            }
                            let stepData = {
                                currentStep: value,
                                title: title,
                                progress: progress,
                            };
                            this.$emit('updateStepper', stepData);
                            this.loanApplicationSteps[value-1].status = 'done';
                            this.loanApplicationSteps[value].status = 'unlocked';
                        } else {
                            if (this.step.currentStep > value) {
                                let stepData = {
                                    currentStep: value,
                                    title: title,
                                    progress: progress,
                                };

                                this.$emit('updateStepper', stepData);
                                this.loanApplicationSteps[value-1].status = 'done';
                                this.loanApplicationSteps[value].status = 'unlocked';
                            } else {
                                if (this.lastUnlocked >= value + 1 || this.lastUnlocked === value) {
                                    let stepData = {
                                        currentStep: value,
                                        title: title,
                                        progress: progress,
                                    };

                                    if (value + 1 > this.lastUnlocked) {
                                        this.lastUnlocked = value + 1;
                                    }
                                    this.$emit('updateStepper', stepData);
                                    this.loanApplicationSteps[value-1].status = 'done';
                                    this.loanApplicationSteps[value].status = 'unlocked';
                                }


                            }

                        }


                    }
                }

            },
            loanApplicationSteps() {
            }
        },

        methods: {
            updateStep(value, progress, title) {
                if (this.step.progress === 0) {

                    let stepData = {
                        currentStep: value,
                        title: title,
                        progress: progress,
                    };
                    this.disclaimerStatus = 'lw-completed-bg';
                    this.$emit('updateStepper', stepData);
                    if (this.lastUnlocked !== false) {
                        if (this.lastUnlocked > 0) {
                            let stepData = {
                                currentStep: value,
                                title: title,
                                progress: progress,
                            };
                            this.disclaimerStatus = 'lw-completed-bg';
                            this.$emit('updateStepper', stepData);
                        }
                    } else {
                        this.lastUnlocked = value + 1;
                    }

                    this.loanApplicationSteps[0].status = 'unlocked';


                }
                if (this.step.progress > 0) {

                    if (this.step.currentStep + 1 === value) {
                        if (this.lastUnlocked < value + 1) {
                            this.lastUnlocked = value + 1;
                        }
                        let stepData = {
                            currentStep: value,
                            title: title,
                            progress: progress,
                        };

                        this.$emit('updateStepper', stepData);
                        this.loanApplicationSteps[value-1].status = 'done';
                        this.loanApplicationSteps[value].status = 'unlocked';
                    } else {
                        if (this.step.currentStep > value) {
                            let stepData = {
                                currentStep: value,
                                title: title,
                                progress: progress,
                            };

                            this.$emit('updateStepper', stepData);
                            this.loanApplicationSteps[value-1].status = 'done';
                            this.loanApplicationSteps[value].status = 'unlocked';
                        } else {
                            if (this.lastUnlocked >= value + 1 || this.lastUnlocked === value) {
                                let stepData = {
                                    currentStep: value,
                                    title: title,
                                    progress: progress,
                                };

                                if (value + 1 > this.lastUnlocked) {
                                    this.lastUnlocked = value + 1;
                                }
                                this.$emit('updateStepper', stepData);
                                this.loanApplicationSteps[value-1].status = 'done';
                                this.loanApplicationSteps[value].status = 'unlocked';
                            }


                        }

                    }


                }
            }
        },

        mounted() {
            // add step data from the database
            let stepData = {
                currentStep: false,
                title: 'Application Start',
                progress: 0,
            };
            this.$emit('updateStepper', stepData);
            if (this.step.currentStep > 0) {
                this.disclaimerStatus = 'lw-completed-bg'
            }
        }
    }

</script>

<style lang="scss"  scoped>


    .lw-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 425px;
        min-height: 100vh;
        background-color: #F7F7F7;
        padding: 50px;
        // NEST
        .cm-logo {
            font-size: 30px;
            margin-bottom: 35px;
            // NEST
            span {
                display: block;
                font-weight: 600;
                line-height: 1.2;

                &:first-child {
                    font-size: 18px;
                }
            }
        }

        .dashboard-main {
            margin-bottom: 35px;
            // NEST
            h4 {
                font-size: 24px;
                font-weight: 600;
                margin-top: 0;
                margin-bottom: 25px;
            }

            .dash-role {
                padding: 10px 15px;
                background-color: #FA501E;
                border-radius: 35px;
                color: #fff;
                text-align: center;
                font-weight: 600;
                color: #000000;
            }
        }

        .lw-in-progress-bg {
            background-color: rgba(237, 164, 0, .45) !important;
        }

        .lw-completed-bg {
            background-color: #61D8AE !important;
            color: #000000;
        }

        .sidebar-links {
            // NEST
            a {
                font-size: 12px;
                font-weight: 600;
                display: block;
                padding: 10px 0;
                border-bottom: 1px solid #707070;
                display: flex;
                align-items: center;
                justify-content: space-between;

                &.active {
                    color: #FA501E;
                }

                .lw-stepper-label {
                    width: 60px;
                }

                .lw-app-step {
                    display: inline-block;
                    text-align: left;
                    width: calc(100% - 80px);
                }

                .lw-status-icon {
                    display: inline-flex;
                    padding: 5px;
                    font-size: 10px;
                    line-height: 1;
                    border-radius: 100%;
                    width: 20px;

                    &.lw-locked {
                        background-color: #BCBCBC;
                    }

                    &.lw-unlocked {
                        background-color: #0A63DD;
                        color: #fff;
                    }
                    &.lw-done {
                        background-color: #61D8AE;
                        color: #fff;
                    }
                }
            }
        }
    }
</style>
