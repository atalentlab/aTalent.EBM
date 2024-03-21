<template>
    <div id="statistics-dashboard">
        <div class="page-header page-header-sticky">
            <div class="ml-auto row">
                <div class="col">
                    <select name="industry_id" v-if="industryList.length" id="industry_id" class="form-control" @change="changeIndustry($event)">
                        <option v-for="industry in industryList" :value="industry.id" :key="industry.id">{{ industry.industry_name }}</option>
                    </select>
                </div>
                <div class="col">
                    <select name="channel_id" v-if="channelList.length" id="channel_id" class="form-control" @change="changeChannel($event)">
                        <option v-for="channel in channelList" :value="channel.id" :key="channel.id">{{ channel.name }}</option>
                    </select>
                </div>
                <div class="col">
                    <select name="period_id" v-if="periodList.length" id="period_id" class="form-control" @change="changePeriod($event)">
                        <option v-for="period in periodList" :value="period.id" :key="period.id">{{ period.name_with_year }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="page-header flex-column align-items-start">
            <h2 class="page-title"> {{ lang[locale]['top-performers'] }}</h2>
            <p> {{ lang[locale]['top-performers-intro'] }} </p>
        </div>

        <div class="row row-cards" :class="isLoading ? ' is-loading': ''" v-if="this.hasPermissionTo('view statistics dashboard.top performers')">
            <div v-for="item in data.top_performers" class="col-12 col-sm-6 col-lg-4 col-xl-4">
                <div class="card card--loader h-100">
                    <div class="card-body pt-5 pb-5 pl-4 pr-4">
                        <div class="loader"></div>
                        <div class="h5 mb-5 text-gray">{{ item['super-text'] }}</div>
                        <div class="d-flex justify-content-between align-items-end">
                            <span class="h4 mr-auto mb-0">{{ item.title }}</span>
                            <span class="h4 ml-2 mb-0">{{ item.data }}</span>
                            <small class="text-muted ml-1 leading-tight">{{ item.suffix }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-header flex-column align-items-start">
            <h2 class="page-title">{{ lang[locale]['top-performers-content'] }}</h2>
            <p>{{ lang[locale]['top-performers-content-intro'] }}</p>
        </div>

        <div :class="isLoading ? ' is-loading': ''">
            <div class="card card--loader" :class="isLoading ? ' is-loading': ''" v-if="this.hasPermissionTo('view statistics dashboard.top performing content')">
                <div class="row">
                    <div class="col-md-3 col-xl-2">
                        <div class="card-body h-100 px-0 border-right-1">
                            <h5 class="card-title pl-5">
                                {{ lang[locale]['select-one'] }}
                            </h5>
                            <div>
                                <label class="radio-plain" v-for="(metric, key) in topPerformingContentMetrics">
                                    <input type="radio" name="top_performing_content_metric" v-bind:value="key" v-model="topPerformingContent.selectedMetric">
                                    <span class="pl-5">{{ lang[locale][metric.name] }} <i class="fe fe-chevron-right"></i></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-xl-10">
                        <div class="card-body pl-md-0">
                            <div class="loader"></div>
                            <div class="table-responsive">
                                <table class="table table-vcenter datatable">
                                    <thead>
                                        <tr role="row">
                                            <th></th>
                                            <th class="text-nowrap">{{ lang[locale]['company-name'] }}</th>
                                            <th class="text-nowrap">{{ lang[locale]['post-title'] }}</th>
                                            <th>{{ lang[locale]['reads'] }}</th>
                                            <th>{{ lang[locale]['likes'] }}</th>
                                            <th>{{ lang[locale]['comments'] }}</th>
                                            <th>{{ lang[locale]['shares-wows'] }}</th>
                                            <th>{{ lang[locale]['view'] }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="data.top_performing_content.length">
                                            <tr role="row" :class="index%2 === 0 ? 'even': 'odd'" v-for="(row, key, index) in data.top_performing_content">
                                                <td>{{ row.rank }}</td>
                                                <td>{{ row.organization_name }}</td>
                                                <td :title="row.title">{{ row.title_trimmed }}</td>
                                                <td>{{ row.view_count }}</td>
                                                <td>{{ row.like_count }}</td>
                                                <td>{{ row.comment_count }}</td>
                                                <td>{{ row.share_count }}</td>
                                                <td>
                                                    <a v-if="row.link" :href="row.link" class="btn btn-outline-primary btn-sm" target="_blank" title="View post"><i class="fe fe-eye"></i></a>
                                                    <span v-else><i class="fe fe-eye-off"></i></span>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr v-else>
                                            <td colspan="8" class="text-center p-5">
                                                <template v-if="this.hasPermissionTo('view statistics dashboard.top performing content')">
                                                    No data available for your search criteria
                                                </template>
                                                <template v-else>
                                                    <p>You need to have a <b>Premium User</b> account or higher to be able to see this data.</p>
                                                    <a target="_blank" class="btn btn-primary d-inline-block mt-3" :href="myProfileUrl + '#membership-plans'">Upgrade now!</a>
                                                </template>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

var langData = require('./lang/messages.json');
var lang = JSON.parse(JSON.stringify(langData));

    export default {
        name: 'StatisticsDashboard',
        props: ['periodList', 'channelList', 'industryList', 'userPermissions', 'topPerformersDataUrl', 'topPerformingContentDataUrl', 'topPerformingContentMetrics', 'myProfileUrl', 'locale'],
        data() {
            return {
                selectedPeriodId: this.periodList.length ? this.periodList[0]['id'] : null,
                selectedChannelId: this.channelList.length ? this.channelList[0]['id'] : null,
                selectedIndustryId: this.industryList.length ? this.industryList[0]['id'] : null,
                topPerformingContent: {
                    selectedMetric: 'top_most_reads',
                },
                isLoading: false,
                data: {
                    'top_performers': [
                        {}, {}, {}, {}, {}, {}, {}, {}, {}, {}
                    ],
                    'top_performing_content': [

                    ],
                },
                lang: lang,
            }
        },
        components: {

        },
        mounted() {
            if (this.periodList.length) {
                this.fetchTopPerformersData();
                this.fetchTopPerformingContentData();
            }
        },
        methods: {
            hasPermissionTo(permission) {
                return this.userPermissions.includes(permission);
            },
            changePeriod(event) {
                this.selectedPeriodId = event.currentTarget.value;
                this.fetchTopPerformersData();
                this.fetchTopPerformingContentData();
            },
            changeChannel(event) {
                this.selectedChannelId = event.currentTarget.value;
                this.fetchTopPerformersData();
                this.fetchTopPerformingContentData();
            },
            changeIndustry(event) {
                this.selectedIndustryId = event.currentTarget.value;
                this.fetchTopPerformersData();
                this.fetchTopPerformingContentData();
            },
            fetchTopPerformersData() {
                let formData = new FormData;
                formData.append('period_id', this.selectedPeriodId);

                if (this.selectedChannelId) {
                    formData.append('channel_id', this.selectedChannelId);
                }

                if (this.selectedIndustryId) {
                    formData.append('industry_id', this.selectedIndustryId);
                }

                $.ajax(this.topPerformersDataUrl, {
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    beforeSend: (xhr) => {
                        this.isLoading = true;
                    },
                    success: (data) => {
                        this.data.top_performers = data;
                    },
                    error: (jqXHR, textStatus, errorThrown) => {},
                    complete: () => {
                        this.isLoading = false;
                    }
                });
            },
            fetchTopPerformingContentData() {
                let formData = new FormData;
                formData.append('period_id', this.selectedPeriodId);

                if (this.selectedChannelId) {
                    formData.append('channel_id', this.selectedChannelId);
                }

                if (this.selectedIndustryId) {
                    formData.append('industry_id', this.selectedIndustryId);
                }

                if (this.topPerformingContent.selectedMetric) {
                    formData.append('top_performing_content_metric', this.topPerformingContent.selectedMetric);
                }

                $.ajax(this.topPerformingContentDataUrl, {
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    beforeSend: (xhr) => {
                        this.isLoading = true;
                    },
                    success: (data) => {
                        this.data.top_performing_content = data;
                    },
                    error: (jqXHR, textStatus, errorThrown) => {},
                    complete: () => {
                        this.isLoading = false;
                    }
                });
            },
        },
        watch: {
            'topPerformingContent.selectedMetric': function (val, oldVal) {
                this.fetchTopPerformingContentData();
            },
        },
    }
</script>
