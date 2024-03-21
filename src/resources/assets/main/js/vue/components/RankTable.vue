<template>
    <div class="u-full-width u-full-height">

        <div class="table-filter-container ">
            <div class="l-flex-grid u-full-width">


                <div class="u-display-inline u-float-left u-hidden-x u-hidden-s u-p-relative">
                    <a href="#" class="js-full-view">
                        <span class="icon-table-expand-collapse-container">
                            <span class="icon-table-expand-collapse">
                                 <span class="icon-chevron-left-b"></span>
                                 <span class="icon-chevron-right-b"></span>
                             </span>
                        </span>
                    </a>
                </div>

                <div class="u-pt-15 u-pl-30 filter-left">
                    <div class="search-container">
                        <input class="form__field form__input--base form__input search-input"
                               v-on:keyup="search($event)" :placeholder="lang[locale]['search']" id="searchField"
                               name="search"/>
                        <span class="icon-search"></span>
                    </div>

                    <div class="filter-button"><span class="icon-filters" v-on:click="toggleMobileFilter"></span></div>

                    <div :class="['mobile-filter', 'u-hidden-l', 'u-hidden-m', {'open' : isMobileFilter}]">
                        <div class="select-container u-pl-15">
                            <select class="form__field form__input--base form__input search-input" name="industry"
                                    v-on:change="filterChanged($event)">
                                <option selected="selected" value="">{{ lang[locale]['filter-industry'] }}</option>
                                <option v-for="val in filters.industries" :value=val.id>
                                    {{ val.industry_name }}
                                </option>

                            </select>
                            <span class="icon-chevron-down"></span>
                        </div>

                        <div class="select-container u-pl-15">
                            <select class="form__field form__input--base form__input search-input" name="channel"
                                    v-on:change="filterChanged($event)">
                                <option selected="selected" value="">{{ lang[locale]['filter-channel'] }}</option>
                                <option v-for="val in filters.channels" :value=val.id>
                                    {{ val.name }}
                                </option>
                            </select>
                            <span class="icon-chevron-down"></span>
                        </div>

                        <div class="select-container last u-pl-15 u-hidden-m u-hidden-l">
                            <select class="form__field form__input--base form__input search-input" name="period"
                                    v-on:change="filterChanged($event)">

                                <option v-for="val in filters.periods" :value=val.id>
                                    {{ getWeekTranslate(val.name_with_year) }}
                                </option>

                            </select>
                            <span class="icon-chevron-down"></span>
                        </div>
                    </div>


                    <div class="select-container u-pl-15 u-hidden-x u-hidden-s">
                        <select class="form__field form__input--base form__input search-input" id="industry"
                                name="industry" v-on:change="filterChanged($event)">
                            <option selected="selected" value="">{{ lang[locale]['filter-industry'] }}</option>
                            <option v-for="val in filters.industries" :value=val.id>
                                {{ val.industry_name }}
                            </option>

                        </select>
                        <span class="icon-chevron-down"></span>
                    </div>

                    <div class="select-container u-pl-15 u-hidden-x u-hidden-s">
                        <select class="form__field form__input--base form__input search-input" id="channel"
                                name="channel" v-on:change="filterChanged($event)">
                            <option selected="selected" value="">{{ lang[locale]['filter-channel'] }}</option>
                            <option v-for="val in filters.channels" :value=val.id>
                                {{ val.name }}
                            </option>
                        </select>
                        <span class="icon-chevron-down"></span>
                    </div>

                </div>


                <div class="filter-right u-pt-15 u-hidden-x u-hidden-s">

                    <div class="select-container u-pl-15">
                        <select class="form__field form__input--base form__input search-input" id="period" name="period"
                                v-on:change="filterChanged($event)">

                            <!--<option value="">Select Time period</option>-->
                            <option v-for="val in filters.periods" :value="val.id">
                                {{ getWeekTranslate(val.name_with_year) }}
                            </option>

                        </select>
                        <span class="icon-chevron-down"></span>
                    </div>

                    <span class="btn-purple" v-on:click="resetAllFilter">{{ lang[locale]['reset-filters'] }}</span>
                </div>


            </div>


        </div>

        <div class="text--center u-hidden-x u-hidden-s">
            <div class="table-row table-header text--center">
                <div class="table-column text--xxxs text--medium rank-column"><span v-if="isShowRank"> {{ lang[locale]['rank'] }}
           </span>
                </div>
                <div class="table-column text--xxxs text--medium double"><span class="u-pl-40">{{ lang[locale]['employer-name'] }}</span>
                </div>
                <div class="table-column text--xxxs text--medium composite">{{ lang[locale]['srm-index'] }}
                    <span class="box--ic-right">
                    <span class="icon-sort composite" v-on:click="sortBy('composite')"></span>
                    <i class="icon-help tooltip">
                        <span class="tooltip-text left">{{ lang[locale]['tip-srm-index'] }}</span>
                    </i>
               </span>
                </div>
                <div class="table-column text--xxxs text--medium">{{ lang[locale]['progression'] }}
                    <span class="box--ic-right">
               <i class="icon-help tooltip">
                   <span class="tooltip-text left"> {{ lang[locale]['tip-progression'] }} </span>
               </i>
               </span>
                </div>
                <div class="table-column text--xxxs text--medium popularity">{{ lang[locale]['popularity'] }}
                    <span class="box--ic-right">
               <span class="icon-sort popularity" v-on:click="sortBy('popularity')"></span>
               <i class="icon-help tooltip">
                   <span class="tooltip-text left"> {{ lang[locale]['tip-popularity'] }} </span>
               </i>
               </span>
                </div>

                <div class="table-column text--xxxxs" v-if="isPopularitySub">{{ lang[locale]['linkedin'] }}</div>
                <div class="table-column text--xxxxs" v-if="isPopularitySub">{{ lang[locale]['wechat'] }}</div>
                <div class="table-column text--xxxxs" v-if="isPopularitySub">{{ lang[locale]['weibo'] }}</div>
                <div class="table-column text--xxxxs" v-if="isPopularitySub">{{ lang[locale]['kanzhun'] }}</div>

                <div class="table-column text--xxxs text--medium activity">{{ lang[locale]['activities'] }}
                    <span class="box--ic-right">
               <span class="icon-sort activity" v-on:click="sortBy('activity')"></span>
               <i class="icon-help tooltip">
                   <span class="tooltip-text right"> {{ lang[locale]['tip-activity'] }} </span>
               </i>
               </span>
                </div>

                <div class="table-column text--xxxxs" v-if="isActivitySub">{{ lang[locale]['linkedin'] }}</div>
                <div class="table-column text--xxxxs" v-if="isActivitySub">{{ lang[locale]['wechat'] }}</div>
                <div class="table-column text--xxxxs" v-if="isActivitySub">{{ lang[locale]['weibo'] }}</div>
                <div class="table-column text--xxxxs" v-if="isActivitySub">{{ lang[locale]['kanzhun'] }}</div>


                <div class="table-column text--xxxs text--medium engagement">{{ lang[locale]['engagement'] }}
                    <span class="box--ic-right">
               <span class="icon-sort engagement" v-on:click="sortBy('engagement')"></span>
               <i class="icon-help tooltip">
                   <span class="tooltip-text right"> {{ lang[locale]['tip-engagement'] }} </span>
               </i>
               </span>
                </div>
                <div class="table-column text--xxxxs" v-if="isEngageSub">{{ lang[locale]['linkedin'] }}</div>
                <div class="table-column text--xxxxs" v-if="isEngageSub">{{ lang[locale]['wechat'] }}</div>
                <div class="table-column text--xxxxs" v-if="isEngageSub">{{ lang[locale]['weibo'] }}</div>
                <div class="table-column text--xxxxs" v-if="isEngageSub">{{ lang[locale]['kanzhun'] }}</div>

            </div>
        </div>

        <div :class="['rank-table ',{'is-filter-open': isMobileFilter }]" id="rankTable"
             v-scroll:debounce="{fn: scrollLoad, debounce: 500 }">

            <!--<div class="rank-table__inner"></div>-->
            <!--<div class="left-purple-col"></div>-->
            <div class="rank-table__inner">
                <div v-for="(item, index) in allList" :class="['table-row', {'mobile-channels-show': isMobChannelShow}]"
                     :id="'row-'+item.id">
                    <div class="table-column rank-column col-purple"> {{ index + 1 }}</div>
                    <div class="table-column double"><img :src="item.logo" alt="" class="company-logo"> <span
                        class="organization-name">{{ item.organization }} </span></div>
                    <div class="table-column">{{ item.composite }}</div>

                    <div
                        :class="['table-column', 'text--green', { 'negative' : isNegative(item.composite_shift), 'neutral' : isNuetral(item.composite_shift) }]">
                        <i class="icon-chevron-up text--strong"></i> <span
                        v-if="(!isNegative(item.composite_shift) && !isNuetral(item.composite_shift))">+</span>{{
                        item.composite_shift }} %
                    </div>

                    <!--NOTE:- popularity value if not filtered by channel-->
                    <div :class="['table-column' ,{'bg-grey' : isPopularitySub}, 'u-hidden-x u-hidden-s']"
                         v-if="!isChannelFilter"> {{ item.popularity.total }}
                        <span class="icon-expand" v-on:click="showChannels('popularity')"
                              v-if="!isPopularitySub"></span>
                    </div>

                    <!--NOTE:- popularity value if filtered by channel-->
                    <div :class="['table-column' ,{'bg-grey' : isPopularitySub}, 'u-hidden-x u-hidden-s']"
                         v-if="isChannelFilter"> {{ item.popularity }}
                    </div>

                    <div class="table-column bg-grey text--xxxxs u-hidden-x u-hidden-s" v-if="isPopularitySub">{{
                        Math.ceil(item.popularity.channels[linkedInId]) }}
                    </div>
                    <div class="table-column bg-grey text--xxxxs u-hidden-x u-hidden-s" v-if="isPopularitySub">{{
                        Math.ceil(item.popularity.channels[wechatId]) }}
                    </div>
                    <div class="table-column bg-grey text--xxxxs u-hidden-x u-hidden-s" v-if="isPopularitySub">{{
                        Math.ceil(item.popularity.channels[weiboId]) }}
                    </div>
                    <div class="table-column bg-grey text--xxxxs u-hidden-x u-hidden-s" v-if="isPopularitySub">{{
                        Math.ceil(item.popularity.channels[kanzhunId]) }}<span class="icon-collapse"
                                                                               v-on:click="closeChannels()"></span>
                    </div>

                    <!--NOTE:- Activity value if NOT filtered by channel-->
                    <div :class="['table-column' ,{'bg-grey' : isActivitySub},'u-hidden-x u-hidden-s']"
                         v-if="!isChannelFilter">{{ item.activity.total }}
                        <span class="icon-expand" v-on:click="showChannels('activity')" v-if="!isActivitySub"></span>
                    </div>
                    <!--NOTE:- Activity value if filtered by channel-->
                    <div :class="['table-column' ,{'bg-grey' : isActivitySub},'u-hidden-x u-hidden-s']"
                         v-if="isChannelFilter">{{ item.activity }}
                    </div>


                    <div class="table-column bg-grey text--xxxxs u-hidden-x u-hidden-s" v-if="isActivitySub">{{
                        Math.ceil(item.activity.channels[linkedInId]) }}
                    </div>
                    <div class="table-column bg-grey text--xxxxs u-hidden-x u-hidden-s" v-if="isActivitySub">{{
                        Math.ceil(item.activity.channels[wechatId]) }}
                    </div>
                    <div class="table-column bg-grey text--xxxxs u-hidden-x u-hidden-s" v-if="isActivitySub">{{
                        Math.ceil(item.activity.channels[weiboId]) }}
                    </div>
                    <div class="table-column bg-grey text--xxxxs u-hidden-x u-hidden-s" v-if="isActivitySub">{{
                        Math.ceil(item.activity.channels[kanzhunId]) }}<span class="icon-collapse"
                                                                             v-on:click="closeChannels()"></span></div>


                    <!--NOTE:- Engagement value if NOT filtered by channel-->
                    <div :class="['table-column' ,{'bg-grey' : isEngageSub}, 'u-hidden-x u-hidden-s']"
                         v-if="!isChannelFilter">{{ item.engagement.total }}
                        <span class="icon-expand last" v-on:click="showChannels('engagement')"
                              v-if="!isEngageSub"></span>
                    </div>

                    <!--NOTE:- Engagement value if filtered by channel-->
                    <div :class="['table-column' ,{'bg-grey' : isEngageSub}, 'u-hidden-x u-hidden-s']"
                         v-if="isChannelFilter">{{ item.engagement }}
                    </div>


                    <div class="table-column bg-grey text--xxxxs u-hidden-x u-hidden-s" v-if="isEngageSub">{{
                        Math.ceil(item.engagement.channels[linkedInId]) }}
                    </div>
                    <div class="table-column bg-grey text--xxxxs u-hidden-x u-hidden-s" v-if="isEngageSub">{{
                        Math.ceil(item.engagement.channels[wechatId]) }}
                    </div>
                    <div class="table-column bg-grey text--xxxxs u-hidden-x u-hidden-s" v-if="isEngageSub">{{
                        Math.ceil(item.engagement.channels[weiboId]) }}
                    </div>
                    <div class="table-column bg-grey text--xxxxs u-hidden-x u-hidden-s" v-if="isEngageSub">{{
                        Math.ceil(item.engagement.channels[kanzhunId]) }}<span class="icon-collapse"
                                                                               v-on:click="closeChannels()"></span>
                    </div>


                    <div class="table-column more-col" v-if="!isChannelFilter"><span class=""
                                                                                     v-on:click="showMobChannelContainer('row-'+item.id)"> <img
                        src="/images/more.svg" alt=""></span></div>

                    <span class="icon-share u-hidden-x u-hidden-s js-share-open" :data-name="item.organization"
                          v-on:click="generateQRCode(item.organization)">
                   <img src="/images/icons/ic_share.svg" alt="">
               </span>


                    <div class="mobile-channels-container u-hidden-m u-hidden-l">

                        <div class="mob-channels-list">

                            <div class="mob-channel-item purple">
                                <div class="l-container-flex align-left">
                                    <div class="u-display-inline text--center" v-on:click="showMobChannels('purple')">
                                        <span class="">Popularity</span>
                                        <div class="text--xxxs text--medium">{{ item.popularity.total }}</div>
                                    </div>
                                    <div class="u-display-inline u-pl-30 channel-details vertical-center">
                              <span class="u-display-inline u-pr-5" v-for="(channel , key) in item.popularity.channels">
                                  <i :class="[ {'icon-linkedin': key == 1 ,'icon-wechat' : key == 2,'icon-weibo' : key == 3, 'icon-kanzhan' : key == 4 }, 'channel-icon']"></i>
                                  <span class="text--xxxxs u-pr-5 channel-text"> {{ Math.ceil(channel) }}</span>
                              </span>
                                    </div>

                                </div>

                                <span class="icon-chevron-right-b u-p-15" v-on:click="showMobChannels('purple')"></span>

                                <span class="icon-share u-hidden-m u-hidden-l js-share-open"
                                      :data-name="item.organization" v-on:click="generateQRCode(item.organization)">
                               <img src="/images/icons/ic_share.svg" alt="">
                           </span>
                            </div>

                            <div class="mob-channel-item purple-medium">

                                <div class="l-container-flex align-left">
                                    <div class="u-display-inline text--center"
                                         v-on:click="showMobChannels('purple-medium')">
                                        <span class="">Activity</span>
                                        <div class="text--xxxs text--medium">{{ item.activity.total }}</div>
                                    </div>
                                    <div class="u-display-inline u-pl-30 channel-details vertical-center">
                                   <span class="u-display-inline u-pr-5"
                                         v-for="(channel , key) in item.activity.channels">
                                    <i :class="[ {'icon-linkedin': key == 1 ,'icon-wechat' : key == 2,'icon-weibo' : key == 3, 'icon-kanzhan' : key == 4 }, 'channel-icon']"></i>
                                       <span class="text--xxxxs u-pr-5 channel-text"> {{ Math.ceil(channel) }}</span>
                                   </span>
                                    </div>

                                </div>


                                <span class="icon-chevron-right-b u-p-15"
                                      v-on:click="showMobChannels('purple-medium')"></span>

                                <span class="icon-share u-hidden-m u-hidden-l js-share-open"
                                      :data-name="item.organization" v-on:click="generateQRCode(item.organization)">
                               <img src="/images/icons/ic_share.svg" alt="">
                           </span>
                            </div>
                            <div class="mob-channel-item purple-light">

                                <div class="l-container-flex align-left">
                                    <div class="u-display-inline text--center"
                                         v-on:click="showMobChannels('purple-light')">
                                        <span class="">Engagement</span>
                                        <div class="text--xxxs text--medium">{{ item.engagement.total }}</div>
                                    </div>
                                    <div class="u-display-inline u-pl-30 channel-details vertical-center">
                                   <span class="u-display-inline u-pr-5"
                                         v-for="(channel , key) in item.engagement.channels">
                                    <i :class="[ {'icon-linkedin': key == 1 ,'icon-wechat' : key == 2,'icon-weibo' : key == 3, 'icon-kanzhan' : key == 4 }, 'channel-icon']"></i>
                                       <span class="text--xxxxs u-pr-5 channel-text"> {{ Math.ceil(channel) }}</span>
                                   </span>
                                    </div>

                                </div>

                                <span class="icon-chevron-right-b u-p-15"
                                      v-on:click="showMobChannels('purple-light')"></span>
                                <span class="icon-share u-hidden-m u-hidden-l js-share-open"
                                      :data-name="item.organization" v-on:click="generateQRCode(item.organization)">
                               <img src="/images/icons/ic_share.svg" alt="">
                           </span>
                            </div>


                        </div>

                    </div>


                </div>
                <div class="table-row" id="bottomLoader" v-if="isNextPage">
                    <div class="table-column rank-column col-purple"></div>
                    <div class="table-column double loading-section text--center">
                        <img src="/images/loading.svg" alt="">
                    </div>
                </div>
            </div>

            <!--no result message-->
            <div v-if="!hasResult">
                <div class="u-bg-white u-full-width text--center u-p-15 u-mt-60">
                    {{ lang[locale]['no-organization-found-1']}}<a href="#contact-form"
                                                                   class="js-nav-item btn-link--red_purple u-plr-5"
                                                                   data-id="contact-form">{{
                    lang[locale]['no-organization-found-cta'] }}</a>{{ lang[locale]['no-organization-found-2']}}
                </div>
            </div>
            <!--no result message-->

            <div class="u-mtb-30 text--center" v-if="isFromHash">
                <span class="btn-link--purple text--xxs text--medium u-ptb-15" v-on:click="resetAllFilter">{{ lang[locale]['show-all']}}</span>
            </div>


        </div>


        <div class="u-p-15 u-hidden-m u-hidden-l">

            <div class="text--xxxxs text--grey text--center"> {{ lang[locale]['no-org-text']}}</div>

            <div class="btn-wrap u-mtb-15 u-float-right u-ml-15 u-full-width">
                <a href="#" class="btn btn-red js-nav-item" data-id="contact-form">
                    <span class="btn-over btn-over-red"></span>
                    <span class="btn-text">{{lang[locale]['no-org-cta']}}</span>
                </a>
            </div>

        </div>


    </div>

</template>

<script>

    var axios = require('axios');
    var _ = require('lodash.debounce');
    var langData = require('../lang/messages.json');
    var lang = JSON.parse(JSON.stringify(langData));


    export default {
        name: "RankTable",
        data() {
            return {
                isLoading: false,
                listItem: '',
                allList: [],
                page: 1,
                pageUrl: '',
                isNextPage: true,
                total: 0,
                linkedInId: '1',
                wechatId: '2',
                weiboId: '3',
                kanzhunId: '4',
                isPopularitySub: false,
                isActivitySub: false,
                isEngageSub: false,
                bottomLoaderId: "bottomLoader",
                filters: {
                    channels: [],
                    industries: [],
                    periods: []
                },
                searchQuery: '',
                filterIndustryId: '',
                filterChannelId: '',
                filterPeriodId: '',
                filterPeriodName: '',
                isSearchFilter: false,
                orderBy: 'composite',
                orderByDirection: 'desc',
                isSortedColChanged: false,
                isMobileFilter: false,
                isMobChannelShow: false,
                isChannelFilter: false,
                lastPeriod: '',
                isShowRank: true,
                isFromHash: false,
                locale: '',
                lang: lang,
                hasResult: true,
            }
        },

        created() {


            var hashStr = window.location.hash.substr(1);
            var searchQuery = '';

            if (hashStr.split('=')[0] == 'organization') {
                searchQuery = decodeURIComponent(hashStr.split('=')[1]);
            }

            const container = document.getElementById('js-main-table');
            this.pageUrl = container.getAttribute('data-url');
            this.filters.channels = JSON.parse(container.getAttribute('data-channels'));
            this.filters.industries = JSON.parse(container.getAttribute('data-industries'));
            this.filters.periods = JSON.parse(container.getAttribute('data-periods'));
            this.locale = container.getAttribute('data-locale');

            if (this.filters.periods.length > 0) {
                this.lastPeriod = this.filters.periods[0]['id'];
                this.filterPeriodId = this.lastPeriod;
            }

            if (searchQuery != undefined && searchQuery != '') {
                this.searchQuery = searchQuery;
                this.isFromHash = true;
                this.scrollToTable();
            }

            this.getRankList();
        },

        methods: {
            getRankList() {
                this.hasResult = true;
                this.isLoading = true;
                axios.post(this.pageUrl, {
                    page: this.page,
                    organization: this.searchQuery,
                    period_id: this.filterPeriodId,
                    industry_id: this.filterIndustryId,
                    channel_id: this.filterChannelId,
                    order_by: this.orderBy,
                    order_by_direction: this.orderByDirection,

                }).then((res) => {
                    this.allList = this.allList.length > 0 ? this.allList.concat(res.data.data) : res.data.data;
                    this.page = this.page + 1;
                    if (res.data.meta.current_page == res.data.meta.last_page) {
                        this.isNextPage = false;
                    }

                    this.hasResult = this.allList.length > 0 ? true : false;

                    this.isLoading = false;
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            resetAllFilter() {
                this.isNextPage = true;
                this.allList = [];
                this.page = 1;
                this.searchQuery = '';
                this.filterPeriodId = this.lastPeriod;
                this.filterChannelId = '';
                this.filterIndustryId = '';
                this.orderBy = 'composite';
                this.orderByDirection = 'desc';
                this.isChannelFilter = false;
                this.isShowRank = true;
                this.isFromHash = false;

                document.getElementById('industry').options.selectedIndex = 0;
                document.getElementById('channel').options.selectedIndex = 0;
                document.getElementById('period').options.selectedIndex = 0;
                document.getElementById('searchField').value = "";
                $('.icon-sort').removeClass('asc');
                $('.icon-sort').removeClass('desc');
                $('.table-column').removeClass('text--purple');

                this.getRankList();
            },

            scrollLoad(e, position) {

                if (this.isNextPage) {
                    var container = document.getElementById("rankTable");
                    var bottomLoader = document.getElementById("bottomLoader");

                    // console.log('scrolling :  '+container.scrollTop + ' '+container.offsetHeight +' '+(bottomLoader.scrollTop + 20) );
                    if ((bottomLoader.offsetTop) <= (container.scrollTop + container.offsetHeight)) {

                        if (this.isNextPage && !this.isLoading) {
                            this.getRankList();
                        }
                    }
                }
            },

            filterChanged(event) {
                if (event.target.name == "industry") {
                    this.filterIndustryId = event.target.value;
                }

                if (event.target.name == "channel") {
                    this.filterChannelId = event.target.value;

                    this.isChannelFilter = this.filterChannelId.length > 0 ? true : false;
                }

                if (event.target.name == "period") {
                    this.filterPeriodId = event.target.value;
                }

                this.isNextPage = true;
                this.allList = [];
                this.page = 1;
                this.isShowRank = false;

                this.getRankList();
            },

            sortBy(val) {

                $('.icon-sort').removeClass('asc');
                $('.icon-sort').removeClass('desc');
                $('.table-column').removeClass('text--purple');

                if (this.orderBy != val) {
                    this.isSortedColChanged = true;
                } else {
                    this.isSortedColChanged = false;
                }

                if (val == "composite") {
                    this.orderBy = 'composite';
                } else if (val == "popularity") {
                    this.orderBy = 'popularity';
                } else if (val == "activity") {
                    this.orderBy = 'activity';
                } else if (val == "engagement") {
                    this.orderBy = 'engagement';
                }


                if (this.orderByDirection === 'desc') {
                    if (this.isSortedColChanged) {
                        this.orderByDirection = 'desc';
                    } else {
                        this.orderByDirection = 'asc';
                    }
                    $('.icon-sort.' + this.orderBy).addClass(this.orderByDirection);
                    $('.table-column.' + this.orderBy).addClass('text--purple');
                } else {
                    this.orderByDirection = 'desc';
                    $('.icon-sort.' + this.orderBy).addClass('desc');
                    $('.table-column.' + this.orderBy).addClass('text--purple');
                }


                this.allList = [];
                this.page = 1;
                this.isNextPage = true;
                this.isShowRank = false;

                this.getRankList();

            },

            search: _(function (event) {
                this.page = 1;
                if ((event.target.value).length >= 2) {
                    this.searchQuery = event.target.value;
                    this.isNextPage = true;
                    this.allList = [];
                    this.isShowRank = false;
                    this.getRankList();
                } else if ((event.target.value).length == 0) {
                    this.searchQuery = "";
                    this.isNextPage = true;
                    this.allList = []
                    this.getRankList();
                }
            }, 600),

            toggleMobileFilter() {
                this.isMobileFilter = this.isMobileFilter ? false : true;
            },

            showChannels(val) {
                if (!this.isChannelFilter) {
                    if (val === 'popularity') {
                        this.isPopularitySub = true;
                        this.isActivitySub = false;
                        this.isEngageSub = false;
                    }
                    if (val === 'engagement') {
                        this.isPopularitySub = false;
                        this.isActivitySub = false;
                        this.isEngageSub = true;
                    }
                    if (val === 'activity') {
                        this.isPopularitySub = false;
                        this.isActivitySub = true;
                        this.isEngageSub = false;
                    }
                }
            },

            closeChannels() {
                this.isPopularitySub = false;
                this.isActivitySub = false;
                this.isEngageSub = false;
            },

            isNegative(value) {
                if (value < 0) {
                    return true;
                }
            },

            isNuetral(value) {
                if (value == 0) {
                    return true;
                }
            },

            getWeekTranslate(value) {
                if (value.length > 0) {
                    return value.replace("Week", lang[this.locale]['week']);
                }
            },

            showMobChannelContainer(id) {

                var row = $("#" + id);
                if (row.hasClass('open')) {
                    row.removeClass('open');
                } else {
                    $('.table-row').removeClass('open');
                    $('.mob-channel-item').removeClass('open');
                    row.addClass('open');
                }
            },

            showMobChannels(className) {

                if ($('.mob-channel-item.' + className).hasClass('open')) {
                    $('.mob-channel-item.' + className).removeClass('open');
                } else {
                    $('.mob-channel-item').removeClass('open');
                    $('.mob-channel-item.' + className).addClass('open')
                }
            },

            generateQRCode(companyName) {

                var canvas = document.getElementById('qrcode');
                var lang = this.locale == 'zh' ? '/zh' : '';

                var link = window.location.origin + lang + '/#organization=' + companyName;

                QRCode.toCanvas(canvas, link, function (error) {
                    if (error) console.error(error)
                    $shareLightbox.open();
                });

                $('.js-linkedin-share').attr('data-url', link);
                $('.js-weibo-share').attr('data-url', link);
            },

            scrollToTable() {
                var position = $(window).width() > 767 ? 100 : 0;
                var scrollToTop = $("#discover-ranking").offset().top - position;
                $('html, body').animate({
                    scrollTop: scrollToTop
                }, 600);
            }

        },
        watch: {
            isChannelFilter() {
                if (this.isChannelFilter) {
                    this.closeChannels();
                }
            },
            searchQuery() {
                document.getElementById('searchField').value = this.searchQuery;
            }
        },

        mounted() {
        }
    }
</script>
