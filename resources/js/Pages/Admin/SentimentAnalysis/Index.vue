<template>

    <div>
        <h1>Sentiment Analysis</h1>

        <div class="col-md-12">


            <v-data-table
                v-if="this.datatable.items"
                v-model:items-per-page="this.datatable.itemsPerPage"
                :headers="this.datatable.headers"
                :items="this.datatable.items"
                item-value="name"
                class="elevation-1"
            >

                <template v-slot:item.user="{ item }">
                    <div class="twitter-username"><a :href="'https://twitter.com/'+item.raw.user" target="_new">@{{item.raw.user}}</a>
                    </div>
                </template>
                <template v-slot:item.user_mentions="{ item }">
                    <div class="row">
                        <div v-if="item.raw.user_mentions" v-for="usr in item.raw.user_mentions"
                             style="margin-top: 7px; margin-bottom: 7px">
                            <div class="twitter-username "><a :href="'https://twitter.com/'+usr" target="_new">
                                @{{usr}} </a></div>
                        </div>
                    </div>


                </template>
                <template v-slot:item.sentiment="{ item }">
                    <div class="default-sentiment"
                         :class="{'negative-sentiment': (item.raw.sentiment === 'negative'), 'positive-sentiment': (item.raw.sentiment === 'positive'), 'neutral-sentiment': (item.raw.sentiment === 'neutral')}">
                        {{item.raw.sentiment}}
                    </div>
                </template>
                <template v-slot:item.text="{ item }">
                    {{item.raw.text}}
                </template>
                <template v-slot:item.actions="{ item }">
                    <!--                    {{item.raw}}-->
                </template>
                <template v-slot:no-data>
                    <v-btn color="primary" @click="initialize">
                        Reset
                    </v-btn>
                </template>
            </v-data-table>

            <!--            <DataTable :datatable="datatable" :data="data" ></DataTable>-->
            <!--            <Pagination :links=" data.roles.links" :data=" data.roles"/>-->
        </div>
    </div>
</template>

<script>
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import DataTable from '../../Shared/DataTable.vue';
    import Pagination from '../../../Components/Pagination.vue';

    export default {
        props: ['data'],
        components: {AdminLayout, DataTable, Pagination},
        layout: AdminLayout,
        data: () => {
            return {
                datatable: {
                    modalComponent: 'edit-role',
                    headers: [
                        {
                            title: 'Tweet',
                            align: 'start',
                            sortable: true,
                            key: 'text',
                            width: '30%'
                        },
                        {
                            title: 'Twitter Username',
                            align: 'start',
                            sortable: true,
                            key: 'user',
                            width: '5%'
                        },
                        {
                            title: 'User Mentions',
                            align: 'start',
                            sortable: true,
                            key: 'user_mentions',
                            width: '5%'
                        },

                        {
                            title: 'Sentiments',
                            align: 'start',
                            sortable: true,
                            key: 'sentiment',
                            width: '5%'
                        },
                        {
                            title: 'Place',
                            align: 'start',
                            sortable: true,
                            key: 'place',
                        },
                        {
                            title: 'Actions',
                            key: 'actions',
                            sortable: false
                        },
                    ],
                    items: false,
                    itemsPerPage: 10,
                    editLink: '/role-action',
                    action: '_role_edit',
                    modal: true,
                    view: true,
                    viewLink: false,
                    formData: {
                        action: 'edit',
                        edit: true
                    },
                    content: {
                        create: {
                            title: 'Edit role'
                        }
                    },
                },
            }
        },
        mounted() {
            this.datatable.items = this.data.analysis
        }

    }
</script>

<style scoped lang="scss">
    .twitter-username a {
        color: #fff;
        text-decoration: none;
        background: #144f9f;
        padding: 5px;
        border-radius: 8px;
        margin: 2px;
    }

    .negative-sentiment {
        color: #000 !important;
        text-decoration: none;
        background: #f00 !important;
        padding: 5px;
        border-radius: 8px;
        margin: 2px;
    }

    .positive-sentiment {
        color: #000 !important;
        text-decoration: none;
        background: #66FF00 !important;
        padding: 5px;
        border-radius: 8px;
        margin: 2px;
    }

    .neutral-sentiment {
        color: #000 !important;
        text-decoration: none;
        background: #d3d0c9 !important;
        padding: 5px;
        border-radius: 8px;
        margin: 2px;
    }

    .default-sentiment {
        font-weight: bold;
        color: #fff;
        text-decoration: none;
        background: #144f9f;
        padding: 10px;
        border-radius: 8px;
    }


</style>
