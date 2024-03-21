<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="upgrade-account-modal" :data-loading="isLoading ? 'true' : 'false'">
        <div class="modal-dialog" role="document">
            <div class="loader"></div>
            <div class="modal-content">
                <template v-if="showSuccessMessage">
                    <div class="modal-header">
                        <h5 class="modal-title">Upgrade Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Thank you, your request was sent successfully! We will come back to you very shortly.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
                    </div>
                </template>
                <template v-else>
                    <validation-observer v-slot="{ invalid, handleSubmit, reset }" ref="observer" tag="div">
                        <form ref="upgrade-account-form" @submit.prevent="handleSubmit(onSubmit)" @reset.prevent="reset">
                            <div class="modal-header">
                                <h5 class="modal-title">Upgrade Account</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Please confirm your phone number so we can get back to you as soon as possible.</p>
                                <div class="form-group">
                                    <label class="form-label" for="account-upgrade-phone">Organization  <span class="form-required">*</span></label>
                                    <input type="text" class="form-control" v-model="form.organization" id="account-upgrade-organization" readonly>
                                </div>
                                <validation-provider rules="required|min:8|max:15" v-slot="{ dirty, valid, invalid, passed, failed, errors }" tag="div" name="phone">
                                    <div class="form-group" v-bind:class="{ 'is-valid': passed, 'is-invalid': failed }">
                                        <label class="form-label" for="account-upgrade-phone">Phone  <span class="form-required">*</span></label>
                                        <input type="text"
                                               class="form-control"
                                               v-model="form.phone"
                                               name="phone"
                                               id="account-upgrade-phone"
                                               placeholder="Your phone number..."
                                        >
                                        <div class="invalid-feedback" v-show="errors">{{ errors[0] }}</div>
                                    </div>
                                </validation-provider>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
                                <a :href="myProfileUrl + '#membership-plans'" target="_blank" class="btn btn-link">See plans</a>
                                <button type="submit" class="btn btn-primary">Confirm</button>
                            </div>
                        </form>
                    </validation-observer>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import { ValidationProvider } from 'vee-validate';
    import { ValidationObserver } from 'vee-validate';
    import { extend } from 'vee-validate';
    import * as rules from 'vee-validate/dist/rules';
    import { messages } from 'vee-validate/dist/locale/en.json';

    Object.keys(rules).forEach(rule => {
        extend(rule, {
            ...rules[rule], // copies rule configuration
            message: messages[rule] // assign message
        });
    });

    export default {
        name: 'UpgradeAccountModal',
        props: ['userInfo', 'upgradeAccountUrl', 'myProfileUrl'],
        components: {
            ValidationProvider,
            ValidationObserver
        },
        data() {
            return {
                isLoading: false,
                showSuccessMessage: false,
                form: {
                    phone: this.userInfo.phone,
                    organization: this.userInfo.organization.name
                },
            }
        },
        mounted() {
            $('#upgrade-account-modal').on('hidden.bs.modal', (e) => {
                this.resetForm();
            });
        },
        methods: {
            onSubmit() {
                let formData = new FormData;
                formData.append('phone', this.form.phone);

                $.ajax(this.upgradeAccountUrl, {
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    beforeSend: (xhr) => {
                        this.isLoading = true;
                    },
                    success: (data) => {
                        this.showSuccessMessage = true;
                    },
                    error: (jqXHR, textStatus, errorThrown) => {},
                    complete: () => {
                        this.isLoading = false;
                    }
                });
            },
            resetForm() {
                this.showSuccessMessage = false;
                if (this.$refs['upgrade-account-form']) {
                    this.$refs['upgrade-account-form'].reset();
                }
            }
        }
    }
</script>
