<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ cardTitle }}</h3>
                <div class="card-options">
                    <button v-if="isActive" type="button" class="btn btn-sm btn-success mr-2" :class="canAddNewItems() ? '' : ' disabled'"  @click="onNewMembershipClick($event)"><i class="fe fe-plus"></i> {{ lang[locale]['new-membership']}} {{ maxItems }}{{ lang[locale]['new-membership-suffix']}}</button>
                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <p v-if="helpMessage" class="align-items-center text-muted">
                    <i class="fe fe-info mr-1"></i>
                    {{ helpMessage }}
                </p>
                <ul class="dd js-sortable">
                    <li class="dd-item js-dd-item" v-for="membership in memberships" :data-id="membership.id">
                        <div class="dd-content card-sm" :class="isActive ? '': ' disabled'">
                            <span class="dd-handle">
                                <i class="fe" :class="isActive ? ' fe-move': ' fe-minus'"></i>
                            </span>
                            <div class="row align-items-center card-header">
                                <input type="hidden" :name="'memberships[' + membership.id + '][order]'" v-model="membership.order" :disabled="!isActive">
                                <input type="hidden" :name="'memberships[' + membership.id + '][id]'" v-model="membership.id" :disabled="!isActive">

                                <div class="col">
                                    <div class="form-group mb-0">
                                        <select class="form-control" :name="'memberships[' + membership.id + '][role_id]'" :disabled="!isActive" :required="isActive" v-model="membership.role_id">
                                            <option value="" :selected="!membership.role_id">{{ lang[locale]['select-role']}}</option>
                                            <option v-for="role in rolesList" :value="role.id" :key="role.id">{{ role.name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-0">
                                        <div class="input-group js-date-input">
                                            <input type="text"
                                                   :name="'memberships[' + membership.id + '][expires_at]'"
                                                   class="form-control"
                                                   data-mask="0000-00-00"
                                                   data-date-format="YYYY-MM-DD"
                                                   data-mask-clearifnotmatch="true"
                                                   placeholder="0000-00-00"
                                                   autocomplete="off"
                                                   maxlength="10"
                                                   :value="membership.expires_at.substring(0, 10)"
                                                   :data-parsley-mindate=getMinDate()
                                                   :disabled="!isActive"
                                                   :required="isActive"
                                            >
                                            <div v-if="isActive" class="input-group-append">
                                                <button type="button" class="btn btn-sm btn-light" title="15 days" data-seconds="1296000" @click="onTimeAddClick($event, membership)">15d</button>
                                                <button type="button" class="btn btn-sm btn-light" title="30 days" data-seconds="2592000" @click="onTimeAddClick($event, membership)">30d</button>
                                                <button type="button" class="btn btn-sm btn-light" title="1 year" data-seconds="31536000" @click="onTimeAddClick($event, membership)">1y</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="membership.time_left" class="col-auto">
                                    {{ membership.time_left }} left
                                </div>
                                <div v-if="!isActive" class="col-auto">
                                    duration {{ membership.duration }}
                                </div>
                                <div class="col-auto">
                                    <div class="form-group mb-0">
                                        <label class="custom-switch d-block" :for="'memberships[' + membership.id + '][is_trial]'">
                                            <input type="checkbox" :disabled="!isActive" :id="'memberships[' + membership.id + '][is_trial]'" :name="'memberships[' + membership.id + '][is_trial]'" v-model="membership.is_trial" class="custom-switch-input" value="1">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">{{ lang[locale]['select-role']}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div v-if="isActive" class="col-auto">
                                    <button class="btn btn-sm btn-danger" type="button" title="Delete" @click="onRowDeleteClick($event, membership.id)">
                                        <i class="fe fe-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</template>

<script>

var langData = require('./lang/messages.json');
var lang = JSON.parse(JSON.stringify(langData));

    export default {
        name: 'Memberships',
        props: ['rolesList', 'initialMemberships', 'cardTitle', 'isActive', 'helpMessage', 'maxItems', 'locale'],
        data() {
            return {
                memberships: this.initialMemberships,
                lang: lang
            }
        },
        components: {

        },
        mounted() {
            this.$container = $(this.$el);
            this.initSort();
        },

        methods: {
            getMinDate() {
                return (new Date()).toISOString().substring(0, 10);
            },
            onRowDeleteClick(e, membershipId) {
                e.preventDefault();

                if (!confirm('Are you sure you want to delete this?')) {
                    return false;
                }

                this.memberships.forEach((membership, index) => {
                    if (membership.id === membershipId) {
                        this.memberships.splice(index, 1);
                    }
                });

                this.toggleRolesInput();
            },
            canAddNewItems() {
                return this.memberships.length < this.maxItems && this.isActive;
            },
            onNewMembershipClick(e) {
                e.preventDefault();

                if (!this.canAddNewItems()) return false;

                this.memberships.push({
                    id: Math.floor(Math.random() * -10000),
                    role_id: "",
                    expires_at: "",
                    is_trial: 0,
                    order: 0,
                    time_left: "",
                    duration: "",
                });

                this.updateOrder();
            },
            onTimeAddClick(e, membership) {
                e.preventDefault();

                const seconds = e.currentTarget.getAttribute('data-seconds');
                membership.expires_at = (new Date()).addSeconds(Number(seconds)).toISOString().substring(0, 10);
            },
            updateOrder() {
                let counter = 0;

                this.$container.find('.js-dd-item').each((key, item) => {
                    const id = $(item).data('id');

                    this.memberships.forEach((membership, index) => {
                        if (membership.id === id) {
                            membership.order = counter;
                        }
                    });

                    counter++;
                });

                if (this.memberships.length) {
                    // remove the draggable list empty element when we membership list items
                    this.$container.find('.dd-empty').remove();
                }

                this.toggleRolesInput();
            },
            onChangeRole(e) {
                const val = e.currentTarget.value;
                const id = $(e.currentTarget).closest('.js-dd-item').data('id');

                this.memberships.forEach((membership, index) => {
                    if (membership.id === id) {
                        membership.role_id = val;
                    }
                });
            },
            initSort() {
                if (!this.isActive) return false;

                this.$container.find('.js-sortable').nestable({
                    maxDepth: 1,
                    callback: ($container, $element) => {
                        this.updateOrder();
                    }
                });

                this.updateOrder();
            },
            toggleRolesInput() {
                if (this.isActive) {
                    const $rolesInput = $('#roles');
                    const selectize = $rolesInput[0].selectize;

                    if (this.memberships.length) {
                        $rolesInput.attr('disabled', 'disabled');
                        if (selectize) {
                            selectize.disable();
                        }
                    }
                    else {
                        $rolesInput.removeAttr('disabled');
                        if (selectize) {
                            selectize.enable();
                        }
                    }
                }
            }
        },
        watch: {

        },
    }
</script>
