<template>
    <div id="my-organization">
        <div class="page-header">
            <div class="ml-auto">
                <select name="period_id" v-if="periodList.length" id="period_id" class="form-control" @change="changePeriod($event)">
                    <option v-for="period in periodList" :value="period.id" :key="period.id">{{ period.name_with_year }}</option>
                </select>
            </div>
        </div>
        <div class="row row-cards" :class="isLoading ? ' is-loading': ''">
            <div class="col-md-8">
                <div class="card h-100" v-if="this.hasPermissionTo('view my organization.company info')">
                    <div class="card-body position-relative">
                        <div class="row">
                            <div class="col-auto">
                                <span v-if="organizationData.logo" class="avatar avatar-contain avatar-transparent avatar-xl" :style="{ backgroundImage: `url('${organizationData.logo}')` }"></span>
                                <span v-else class="avatar avatar-xl">{{ organizationData.name.charAt(0) }}</span>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col"><h1 class="h1 mb-0">{{ organizationData.name }}</h1></div>
                                    <div class="col-auto">
                                        <div class="avatar-list">
                                            <template v-for="channel in organizationData.channels">
                                                <a v-if="channel.is_external_link" :title="channel.name" :href="channel.url" target="_blank"  class="avatar avatar-rounded" :style="{ backgroundImage: `url('${channel.logo}')` }"></a>
                                                <a v-else href="javascript:;" class="avatar avatar-rounded" :style="{ backgroundImage: `url('${channel.logo}')` }" data-trigger="click" data-toggle="tooltip" :title="channel.url"></a>
                                            </template>
                                            <a :href="myOrganizationSettingsUrl + '#channels'" v-if="organizationData.can_add_more_channels" class="avatar avatar-rounded" style="font-size:1.2rem;" title="Add social channels">+</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="h5 mb-0 mt-1" v-if="organizationData.translated_name">{{ organizationData.translated_name }}</div>
                                <div class="mt-3" v-if="organizationData.intro">
                                     {{ organizationData.intro }}
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col" v-if="organizationData.industry">
                                        <div class="h5 mb-1">{{ organizationData.industry }}</div>
                                        <i>{{ lang[locale]['industry'] }}</i>

                                    </div>
                                    <div class="col" v-if="organizationData.indexed_since">
                                        <div class="h5 mb-1">{{ organizationData.indexed_since }}</div>
                                        <i>{{ lang[locale]['indexed-since'] }}</i>
                                    </div>
                                </div>
                                <div v-if="organizationData.profile_incomplete" class="row">
                                    <div class="col">
                                        <p>{{ organizationIncompleteMessage }}</p>
                                        <a :href="myOrganizationSettingsUrl" class="btn btn-primary">{{ organizationIncompleteCta }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4" v-if="this.hasPermissionTo('view my organization.rank info')">
                <div class="card card--loader h-100">
                    <div class="card-body p-2 text-center">
                        <div class="loader"></div>
                        <div class="text-right" :class="data.rank_info.srm_score.composite_shift > 0 ? ' text-green' : (data.rank_info.srm_score.composite_shift < 0 ? ' text-red' : ' text-yellow')">
                          <span class="d-inline-flex align-items-center lh-1">
                              {{ data.rank_info.srm_score.composite_shift }}%
                              <i v-if="data.rank_info.srm_score.composite_shift > 0" class="fe fe-trending-up pl-1"></i>
                              <i v-else-if="data.rank_info.srm_score.composite_shift < 0" class="fe fe-trending-down pl-1"></i>
                              <i v-else class="fe fe-minus pl-1"></i>
                          </span>
                        </div>
                        <span class="h1 m-0 position-relative">{{ data.rank_info.srm_score.composite }}
                            <i class="fe fe-help-circle tooltip-toggle" data-toggle="tooltip" data-placement="right" title="The EBM score is formulated with a mix of three parameters: Popularity, Activity and Engagement. It summarizes and qualifies how well an organization is doing with their Employer Branding efforts on social media."></i>
                        </span>
                        <div class="text-muted mb-3">  {{ lang[locale]['ebm-score'] }}</div>
                    </div>
                </div>
                <div class="card card--loader h-100">
                    <div class="card-body p-2 text-center">
                        <div class="loader"></div>
                        <div class="text-right" :class="data.rank_info.rank.shift > 0 ? ' text-green' : (data.rank_info.rank.shift < 0 ? ' text-red' : ' text-yellow')">
                          <span class="d-inline-flex align-items-center lh-1">
                              {{ data.rank_info.rank.shift }}
                              <i v-if="data.rank_info.rank.shift > 0" class="fe fe-trending-up pl-1"></i>
                              <i v-else-if="data.rank_info.rank.shift < 0" class="fe fe-trending-down pl-1"></i>
                              <i v-else class="fe fe-minus pl-1"></i>
                          </span>
                        </div>
                        <span class="h2 m-0 position-relative">{{ data.rank_info.rank.own }}<span class="h5"> of {{ data.rank_info.rank.total }}</span>
                        <i class="fe fe-help-circle tooltip-toggle" data-toggle="tooltip" data-placement="right" title="The EBM Rank sort each organization based on their EBM Score. The weekly progression helps organizations to know how well they are doing compared to the previous week."></i>
                        </span>

                        <div class="text-muted mb-3">{{ lang[locale]['rank'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" v-if="this.hasPermissionTo('view my organization.cross channel stats')">
                <div class="page-header justify-content-center">
                    <h2 class="page-title">{{ lang[locale]['my-organization-intro'] }}</h2>
                </div>
                <div class="row row-cards">
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="card card--loader h-100">
                            <div class="card-body p-2 text-center">
                                <div class="loader"></div>
                                <div class="text-right" :class="String(data.cross_channel_stats.post_count.progression).indexOf('-') !== -1 ? ' text-red' : (data.cross_channel_stats.post_count.progression == 0 ? ' text-yellow' : ' text-green')">
                                  <span class="d-inline-flex align-items-center lh-1">
                                      {{ data.cross_channel_stats.post_count.progression }}
                                      <i v-if="String(data.cross_channel_stats.post_count.progression).indexOf('-') !== -1" class="fe fe-trending-down pl-1"></i>
                                      <i v-else-if="data.cross_channel_stats.post_count.progression == 0" class="fe fe-minus pl-1"></i>
                                      <i v-else class="fe fe-trending-up pl-1"></i>
                                  </span>
                                </div>
                                <div class="h3 m-0">{{ data.cross_channel_stats.post_count.count }}</div>
                                <div class="text-muted mb-3">{{ lang[locale]['social-posts'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="card card--loader h-100">
                            <div class="card-body p-2 text-center">
                                <div class="loader"></div>
                                <div class="text-right" :class="String(data.cross_channel_stats.view_count.progression).indexOf('-') !== -1 ? ' text-red' : (data.cross_channel_stats.view_count.progression == 0 ? ' text-yellow' : ' text-green')">
                                  <span class="d-inline-flex align-items-center lh-1">
                                      {{ data.cross_channel_stats.view_count.progression }}
                                      <i v-if="String(data.cross_channel_stats.view_count.progression).indexOf('-') !== -1" class="fe fe-trending-down pl-1"></i>
                                      <i v-else-if="data.cross_channel_stats.view_count.progression == 0" class="fe fe-minus pl-1"></i>
                                      <i v-else class="fe fe-trending-up pl-1"></i>
                                  </span>
                                </div>
                                <div class="h3 m-0">{{ data.cross_channel_stats.view_count.count }}</div>
                                <div class="text-muted mb-3">{{ lang[locale]['reads'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="card card--loader h-100">
                            <div class="card-body p-2 text-center">
                                <div class="loader"></div>
                                <div class="text-right" :class="String(data.cross_channel_stats.like_count.progression).indexOf('-') !== -1 ? ' text-red' : (data.cross_channel_stats.like_count.progression == 0 ? ' text-yellow' : ' text-green')">
                                  <span class="d-inline-flex align-items-center lh-1">
                                      {{ data.cross_channel_stats.like_count.progression }}
                                      <i v-if="String(data.cross_channel_stats.like_count.progression).indexOf('-') !== -1" class="fe fe-trending-down pl-1"></i>
                                      <i v-else-if="data.cross_channel_stats.like_count.progression == 0" class="fe fe-minus pl-1"></i>
                                      <i v-else class="fe fe-trending-up pl-1"></i>
                                  </span>
                                </div>
                                <div class="h3 m-0">{{ data.cross_channel_stats.like_count.count }}</div>
                                <div class="text-muted mb-3">{{ lang[locale]['likes'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="card card--loader h-100">
                            <div class="card-body p-2 text-center">
                                <div class="loader"></div>
                                <div class="text-right" :class="String(data.cross_channel_stats.comment_count.progression).indexOf('-') !== -1 ? ' text-red' : (data.cross_channel_stats.comment_count.progression == 0 ? ' text-yellow' : ' text-green')">
                                  <span class="d-inline-flex align-items-center lh-1">
                                      {{ data.cross_channel_stats.comment_count.progression }}
                                      <i v-if="String(data.cross_channel_stats.comment_count.progression).indexOf('-') !== -1" class="fe fe-trending-down pl-1"></i>
                                      <i v-else-if="data.cross_channel_stats.comment_count.progression == 0" class="fe fe-minus pl-1"></i>
                                      <i v-else class="fe fe-trending-up pl-1"></i>
                                  </span>
                                </div>
                                <div class="h3 m-0">{{ data.cross_channel_stats.comment_count.count }}</div>
                                <div class="text-muted mb-3">{{ lang[locale]['COMMENTS'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="card card--loader h-100">
                            <div class="card-body p-2 text-center">
                                <div class="loader"></div>
                                <div class="text-right" :class="String(data.cross_channel_stats.share_count.progression).indexOf('-') !== -1 ? ' text-red' : (data.cross_channel_stats.share_count.progression == 0 ? ' text-yellow' : ' text-green')">
                                  <span class="d-inline-flex align-items-center lh-1">
                                      {{ data.cross_channel_stats.share_count.progression }}
                                      <i v-if="String(data.cross_channel_stats.share_count.progression).indexOf('-') !== -1" class="fe fe-trending-down pl-1"></i>
                                      <i v-else-if="data.cross_channel_stats.share_count.progression == 0" class="fe fe-minus pl-1"></i>
                                      <i v-else class="fe fe-trending-up pl-1"></i>
                                  </span>
                                </div>
                                <div class="h3 m-0">{{ data.cross_channel_stats.share_count.count }}</div>
                                <div class="text-muted mb-3">{{ lang[locale]['shares-wows'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="card card--loader h-100">
                            <div class="card-body p-2 text-center">
                                <div class="loader"></div>
                                <div class="text-right" :class="String(data.cross_channel_stats.follower_count.progression).indexOf('-') !== -1 ? ' text-red' : (data.cross_channel_stats.follower_count.progression == 0 ? ' text-yellow' : ' text-green')">
                                  <span class="d-inline-flex align-items-center lh-1">
                                      {{ data.cross_channel_stats.follower_count.progression }}
                                      <i v-if="String(data.cross_channel_stats.follower_count.progression).indexOf('-') !== -1" class="fe fe-trending-down pl-1"></i>
                                      <i v-else-if="data.cross_channel_stats.follower_count.progression == 0" class="fe fe-minus pl-1"></i>
                                      <i v-else class="fe fe-trending-up pl-1"></i>
                                  </span>
                                </div>
                                <div class="h3 m-0">{{ data.cross_channel_stats.follower_count.count }}</div>
                                <div class="text-muted mb-3">{{ lang[locale]['est-total-fan-base'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12" v-if="this.hasPermissionTo('view my organization.competition analysis')">
                <CompetitionAnalysis
                    :channel-list="channelList"
                    :selected-period-id="selectedPeriodId"
                    :data-url="competitionAnalysisDataUrl"
                    :chart-data-url="competitionAnalysisChartDataUrl"
                    :organization-list-url="organizationListUrl"
                    :page-type="pageType"
                    :can-add-multiple-competitors="this.hasPermissionTo('manage multiple competitors')"
                    :my-profile-url="myProfileUrl"
                    :user-info="userInfo"
                    :upgrade-account-url="upgradeAccountUrl"
                    :locale="locale"
                ></CompetitionAnalysis>
            </div>
        </div>
    </div>
</template>

<script>
    import CompetitionAnalysis from './organization/CompetitionAnalysis';
    var langData = require('./lang/messages.json');
    var lang = JSON.parse(JSON.stringify(langData));

    export default {
        name: 'MyOrganization',
        props: ['periodList', 'channelList', 'userPermissions', 'userInfo', 'dataUrl', 'organizationData', 'myOrganizationSettingsUrl', 'organizationIncompleteMessage', 'organizationIncompleteCta', 'competitionAnalysisDataUrl', 'competitionAnalysisChartDataUrl', 'organizationListUrl', 'pageType', 'myProfileUrl', 'upgradeAccountUrl','locale'],
        data() {
            return {
                selectedPeriodId: this.periodList.length ? this.periodList[0]['id'] : null,
                selectedChannelId: this.channelList.length ? this.channelList[0]['id'] : null,
                isLoading: false,
                data: {
                    rank_info: {
                        srm_score: {
                            composite: 'N/A',
                            composite_shift: 0
                        },
                        rank: {
                            own: 0,
                            total: 0,
                            shift: 0
                        }
                    },
                    cross_channel_stats: {
                        post_count: {
                            count: "0",
                            progression: "0"
                        },
                        view_count: {
                            count: "0",
                            progression: "0"
                        },
                        like_count: {
                            count: "0",
                            progression: "0"
                        },
                        comment_count: {
                            count: "0",
                            progression: "0"
                        },
                        share_count: {
                            count: "0",
                            progression: "0"
                        },
                        follower_count: {
                            count: "0",
                            progression: "0"
                        },
                    }
                },
                lang : lang
            }
        },
        components: {
            CompetitionAnalysis
        },
        mounted() {
            if (this.periodList.length) {
                this.fetchData();
            }
        },
        methods: {
            hasPermissionTo(permission) {
                return this.userPermissions.includes(permission);
            },
            changePeriod(event) {
                this.selectedPeriodId = event.currentTarget.value;
                this.fetchData();
            },
            changeChannel(event) {
                this.selectedChannelId = event.currentTarget.value;
            },
            fetchData() {
                let formData = new FormData;
                formData.append('period_id', this.selectedPeriodId);

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
                    },
                    error: (jqXHR, textStatus, errorThrown) => {},
                    complete: () => {
                        this.isLoading = false;
                    }
                });
            }
        },
        watch: {

        },
    }
</script>
