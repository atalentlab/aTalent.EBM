<template>
    <div v-if="selectedPeriodId" :class="isLoading ? ' is-loading': ''">
        <div class="page-header justify-content-center position-relative">
            <div class="position-absolute" style="left: 0;">
                <select name="channel_id" v-if="channelList.length && selectedPeriodId" id="channel_id" class="form-control" @change="changeChannel($event)">
                    <option v-for="channel in channelList" :value="channel.id" :key="channel.id">{{ channel.name }}</option>
                </select>
            </div>
            <h2 class="page-title">{{ lang[locale]['competition-analysis'] }}</h2>
        </div>
        <div class="card card--loader">
            <div class="loader"></div>
            <table class="table card-table table-vcenter text-nowrap datatable table-metrics">
                <tr class="no-hover">
                    <th v-for="(header, index) in data.table.headers" :key="index">
                        <template v-if="header.value">
                            <span :title="header.help && header.help">{{ index == 0 ? lang[locale]['metric'] : header.value }}</span>
                        </template>
                        <template v-else>{{ index == 0 ? lang[locale]['metric'] : header }}</template>
                    </th>
                    <th v-if="pageType === 'my-organization'" class="actions">
                        <button type="button" @click="openAddCompetitorModal" class="btn btn-primary" title="Add a competitor">Add Competitor</button>
                    </th>
                </tr>
                <tr v-for="(row, index) in data.table.rows" :class="index === focusedRowIndex ? 'selected' : ''" @click="onRowClick($event, index)">
                    <td v-for="cell in row">
                        <template v-if="cell">

                            {{ cell.help ? lang[locale][cell.value] : cell.value }}

                            <i v-if="cell.help" class="fe fe-help-circle small text-muted" data-toggle="tooltip" :title="cell.help"></i>
                        </template>
                    </td>
                    <td v-if="pageType === 'my-organization'" class="actions"></td>
                </tr>
            </table>

            <div class="card-body">
                <p class="text-center text-muted"><i class="fe fe-info small"></i> {{ lang[locale]["msg-click-one-row"] }}</p>
                <apexchart type="line" height="350" ref="chart" :options="chartData.options" :series="chartData.series"></apexchart>
            </div>
        </div>

        <template v-if="pageType === 'my-organization'">
            <Modal id="add-competitor-modal" :is-loading="isLoading">
                <template v-if="data.add_competitor_success">
                    <div class="modal-header">
                        <h5 class="modal-title">Add a Competitor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Competitor successfully added to your organization page.</p>
                        <p v-if="!data.can_add_competitors">If you want to add more competitors, please upgrade your account by clicking the button below.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
                        <template v-if="data.can_add_competitors">
                            <button type="button" class="btn btn-primary" @click="resetAddCompetitorForm">Add another competitor</button>
                        </template>
                        <template v-else>
                            <a :href="myProfileUrl + '#membership-plans'" target="_blank" class="btn btn-link">See plans</a>
                            <button type="button" class="btn btn-primary" @click="openUpgradeAccountModal">Upgrade account</button>
                        </template>
                    </div>
                </template>
                <template v-else-if="data.can_add_competitors">
                    <validation-observer v-slot="{ invalid, handleSubmit }" ref="observer" tag="div">
                        <form @submit.prevent="handleSubmit(addCompetitor)">
                            <div class="modal-header">
                                <h5 class="modal-title">Add a Competitor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <validation-provider rules="required" v-slot="{ dirty, valid, invalid, passed, failed, errors }" tag="div" name="competitor">
                                    <div class="form-group" v-bind:class="{ 'is-valid': passed, 'is-invalid': failed }">
                                        <select name="competitor_id"
                                                id="competitor_id"
                                                class="form-control selectize mb-3 js-init-selectize"
                                                data-remote="true"
                                                :data-remote-url="organizationListUrl"
                                                ref="competitor_id"
                                                v-model="addCompetitorForm.selectedId"
                                        >
                                            <option value="">Type your competitor name...</option>
                                        </select>
                                        <div class="invalid-feedback" v-show="errors">{{ errors[0] }}</div>
                                    </div>
                                </validation-provider>

                                <small v-if="!canAddMultipleCompetitors" class="text-muted">You can select 1 competitor to compare with your organization. If you want to add multiple competitors, please request an account upgrade <a href="javascript:;" @click="openUpgradeAccountModal">here</a>.</small>
                                <small v-else class="text-muted">You can add up to 5 competitors.</small>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Confirm</button>
                            </div>
                        </form>
                    </validation-observer>
                </template>
                <template v-else>
                    <div class="modal-header">
                        <h5 class="modal-title">Add a Competitor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>You current subscription plan only allows {{ canAddMultipleCompetitors ? '5 competitors' : '1 competitor'}}.</p>
                        <p>Click below to upgrade your account and track more competitors.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
                        <a :href="myProfileUrl + '#membership-plans'" target="_blank" class="btn btn-link">See plans</a>
                        <button type="button" class="btn btn-primary" @click="openUpgradeAccountModal">Upgrade account</button>
                    </div>
                </template>
            </Modal>

            <UpgradeAccountModal
                :user-info="userInfo"
                :upgrade-account-url="upgradeAccountUrl"
                :my-profile-url="myProfileUrl"
            ></UpgradeAccountModal>
        </template>
    </div>

</template>

<script>
    const selectize = require('../../components/selectize');
    import Vue from 'vue';
    import VueApexCharts from 'vue-apexcharts';
    Vue.use(VueApexCharts);
    import { ValidationProvider } from 'vee-validate';
    import { ValidationObserver } from 'vee-validate';
    import { extend } from 'vee-validate';
    import * as rules from 'vee-validate/dist/rules';
    import { messages } from 'vee-validate/dist/locale/en.json';

    var langData = require('../lang/messages.json');
    var lang = JSON.parse(JSON.stringify(langData));

    Object.keys(rules).forEach(rule => {
        extend(rule, {
            ...rules[rule], // copies rule configuration
            message: messages[rule] // assign message
        });
    });

    import Modal from '../ui/Modal';
    import UpgradeAccountModal from '../UpgradeAccountModal';

    export default {
        name: 'CompetitionAnalysis',
        props: ['selectedPeriodId', 'channelList', 'dataUrl', 'chartDataUrl', 'organizationListUrl', 'pageType', 'canAddMultipleCompetitors', 'myProfileUrl', 'userInfo', 'upgradeAccountUrl', 'locale'],
        data() {
            return {
                addCompetitorForm: {
                    selectedId: null,
                },
                selectedChannelId: this.channelList.length ? this.channelList[0]['id'] : null,
                isLoading: false,
                focusedRowIndex: null,
                data: {
                    can_add_competitors: false,
                    add_competitor_success: false,
                    table: {
                        headers: {
                            0: 'Metric',
                        },
                        rows: {},
                    }
                },
                chartData: {
                    options: {
                        chart: {
                            height: 350,
                            parentHeightOffset: 0,
                            fontFamily: 'inherit',
                            type: 'line',
                            toolbar: {
                                show: false,
                            },
                            animations: {
                                enabled: false
                            },
                            zoom: {
                                enabled: false
                            },
                        },
                        fill: {
                            opacity: 1,
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            width: 2,
                            lineCap: "round",
                            curve: "straight",
                        },
                        grid: {
                            padding: {
                                top: -10,
                                right: 0,
                                left: -4,
                                bottom: -4
                            },
                            strokeDashArray: 4,
                        },
                        title: {
                            text: '',
                            align: 'center'
                        },
                        xaxis: {
                            labels: {
                                padding: 0,
                            },
                            tooltip: {
                                enabled: false
                            },
                           // type: 'datetime',
                        },
                        yaxis: {
                            labels: {
                                padding: 4
                            },
                            //decimalsInFloat: 0,
                        },
                        labels: [],
                        colors: ["#a55eea", "#5eba00", "#fab005", "#cd201f", "#206bc4", "#17a3b8"],
                        legend: {
                            show: true,
                            position: 'bottom',
                            offsetY: 12,
                            markers: {
                                width: 10,
                                height: 10,
                                radius: 100,
                            },
                            itemMargin: {
                                horizontal: 8,
                                vertical: 8
                            },
                        },
                    },
                    series: []
                },
                lang: lang
            }
        },
        components: {
            apexchart: VueApexCharts,
            Modal,
            UpgradeAccountModal,
            ValidationProvider,
            ValidationObserver
        },
        mounted() {
            if (this.selectedPeriodId) {
                this.fetchData();
            }

            $(document).on('change', '#competitor_id', (e) => {
                this.addCompetitorForm.selectedId = e.currentTarget.value;
            });

            $('#add-competitor-modal').on('hidden.bs.modal', (e) => {
                this.resetAddCompetitorForm();
            })
        },
        methods: {
            initSelectize() {
                let $elem = $('.js-init-selectize');

                if ($elem.length && !$elem.hasClass('selectized')) {
                    selectize.init($elem);
                }
            },
            clearSelect(elem) {
                if (!elem) return false;

                elem.value = "";

                if (elem.selectize) {
                    elem.selectize.setValue("");
                    elem.selectize.clearOptions();
                }
            },
            changeChannel(event) {
                this.selectedChannelId = event.currentTarget.value;
                this.fetchData();
                this.fetchChartData();
            },
            addCompetitor() {
                let selectBox = $('#competitor_id')[0];

                if (!confirm('Are you sure you want to select this organization for your competitor analysis? This cannot be changed anymore afterwards.')) {
                    return false;
                }

                this.fetchData(selectBox.value);
            },
            resetAddCompetitorForm() {
                this.addCompetitorForm.selectedId = null;
                this.data.add_competitor_success = false;
                this.clearSelect($('#competitor_id')[0]);

                this.$nextTick(() => {
                    this.initSelectize();
                });
            },
            openAddCompetitorModal() {
                this.initSelectize();
                $('#add-competitor-modal').modal('show');
            },
            closeAddCompetitorModal() {
                $('#add-competitor-modal').modal('hide');
            },
            openUpgradeAccountModal() {
                this.closeAddCompetitorModal();
                $('#upgrade-account-modal').modal('show');
            },
            closeUpgradeAccountModal() {

            },
            onRowClick(event, rowIndex) {
                this.focusedRowIndex = rowIndex;
            },
            fetchData(newCompetitorId = null) {
                let formData = new FormData;
                formData.append('period_id', this.selectedPeriodId);

                if (this.selectedChannelId) {
                    formData.append('channel_id', this.selectedChannelId);
                }

                if (newCompetitorId && this.data.can_add_competitors) {
                    formData.append('competitor_id', newCompetitorId);
                }

                $.ajax(this.dataUrl, {
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    beforeSend: (xhr) => {
                        this.isLoading = true;
                    },
                    success: (data) => {
                        this.data = data;

                        console.log('data:  '+ JSON.stringify(data.table.rows));
                        if (newCompetitorId) {
                            this.fetchChartData();
                        }
                    },
                    error: (jqXHR, textStatus, errorThrown) => {},
                    complete: () => {
                        this.isLoading = false;
                    }
                });
            },
            fetchChartData() {
                let formData = new FormData;
                formData.append('period_id', this.selectedPeriodId);

                if (this.selectedChannelId) {
                    formData.append('channel_id', this.selectedChannelId);
                }

                // only fetch chart data when a row is highlighted
                if (!this.focusedRowIndex) {
                    return false;
                }

                formData.append('metric', this.focusedRowIndex);

                $.ajax(this.chartDataUrl, {
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    beforeSend: (xhr) => {
                        this.isLoading = true;
                    },
                    success: (data) => {
                        let chartData = this.chartData;

                        // find the max amount of decimal spaces to manually update the label on the Y-axis
                        let decimalsInFloat = 0;

                        for (let items of data.series) {
                            for (let val of items.data) {
                                decimalsInFloat = Math.max(decimalsInFloat, Number(val).countDecimals());
                            }
                        }

                        chartData.series = data.series;
                        chartData.options.labels = data.labels;
                        chartData.options.title = data.title;
                        chartData.options.yaxis.decimalsInFloat = decimalsInFloat;

                        this.chartData = chartData;

                        this.$refs.chart.updateOptions({
                            series: data.series,
                            labels: data.labels,
                            title: {
                                text: data.title
                            },
                            yaxis: {
                                decimalsInFloat: decimalsInFloat,
                            }
                        });
                    },
                    error: (jqXHR, textStatus, errorThrown) => {},
                    complete: () => {
                        this.isLoading = false;
                    }
                });
            },
        },
        watch: {
            selectedPeriodId: function() {
                this.fetchData();
                this.fetchChartData();
            },
            focusedRowIndex: function(val) {
                this.fetchChartData();
            },
            // 'data.can_add_competitors': function (val, oldVal) {
            //     this.$nextTick(() => {
            //         this.initSelectize();
            //     });
            // },
        },
    }
</script>
