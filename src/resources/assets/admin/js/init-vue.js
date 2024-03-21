import Vue from 'vue'
import MyOrganization from './vue/MyOrganization';
import StatisticsDashboard from './vue/StatisticsDashboard';
import Memberships from './vue/Memberships';

Vue.config.productionTip = false;

if (document.getElementById('my-organization')) {
    new Vue({
        el: '#my-organization',
        components: {
            MyOrganization
        },
        render(h) {
            return h(MyOrganization, {
                props: {
                    periodList: JSON.parse(this.$el.getAttribute('data-periods')),
                    channelList: JSON.parse(this.$el.getAttribute('data-channels')),
                    organizationListUrl: this.$el.getAttribute('data-organizations-list-url'),
                    userPermissions: JSON.parse(this.$el.getAttribute('data-user-permissions')),
                    userInfo: JSON.parse(this.$el.getAttribute('data-user-info')),
                    organizationData: JSON.parse(this.$el.getAttribute('data-organization-data')),
                    dataUrl: this.$el.getAttribute('data-data-url'),
                    pageType: this.$el.getAttribute('data-page-type'),
                    competitionAnalysisDataUrl: this.$el.getAttribute('data-competition-analysis-data-url'),
                    competitionAnalysisChartDataUrl: this.$el.getAttribute('data-competition-analysis-chart-data-url'),
                    myOrganizationSettingsUrl: this.$el.getAttribute('data-my-organization-settings-url'),
                    organizationIncompleteMessage: this.$el.getAttribute('data-organization-incomplete-message'),
                    organizationIncompleteCta: this.$el.getAttribute('data-organization-incomplete-cta'),
                    myProfileUrl: this.$el.getAttribute('data-my-profile-url'),
                    upgradeAccountUrl: this.$el.getAttribute('data-upgrade-account-url'),
                    locale: this.$el.getAttribute('data-locale'),
                }
            })
        }
    });
}

if (document.getElementById('statistics-dashboard')) {
    new Vue({
        el: '#statistics-dashboard',
        components: {
            StatisticsDashboard
        },
        render(h) {
            return h(StatisticsDashboard, {
                props: {
                    userPermissions: JSON.parse(this.$el.getAttribute('data-user-permissions')),
                    periodList: JSON.parse(this.$el.getAttribute('data-periods')),
                    channelList: JSON.parse(this.$el.getAttribute('data-channels')),
                    industryList: JSON.parse(this.$el.getAttribute('data-industries')),
                    topPerformersDataUrl: this.$el.getAttribute('data-top-performers-data-url'),
                    topPerformingContentDataUrl: this.$el.getAttribute('data-top-performing-content-data-url'),
                    topPerformingContentMetrics: JSON.parse(this.$el.getAttribute('data-top-performing-content-metrics')),
                    myProfileUrl: this.$el.getAttribute('data-my-profile-url'),
                    locale: this.$el.getAttribute('data-locale'),
                }
            })
        }
    });
}

if (document.getElementById('active-memberships')) {
    new Vue({
        el: '#active-memberships',
        components: {
            Memberships
        },
        render(h) {
            return h(Memberships, {
                props: {
                    rolesList: JSON.parse(this.$el.getAttribute('data-roles')),
                    initialMemberships: JSON.parse(this.$el.getAttribute('data-memberships')),
                    cardTitle: this.$el.getAttribute('data-card-title'),
                    isActive: true,
                    helpMessage: this.$el.getAttribute('data-help-text'),
                    maxItems: 2,
                    locale: this.$el.getAttribute('data-locale'),
                }
            })
        }
    });
}

if (document.getElementById('inactive-memberships')) {
    new Vue({
        el: '#inactive-memberships',
        components: {
            Memberships
        },
        render(h) {
            return h(Memberships, {
                props: {
                    rolesList: JSON.parse(this.$el.getAttribute('data-roles')),
                    initialMemberships: JSON.parse(this.$el.getAttribute('data-memberships')),
                    cardTitle: this.$el.getAttribute('data-card-title'),
                    isActive: false,
                    locale: this.$el.getAttribute('data-locale'),
                }
            })
        }
    });
}
