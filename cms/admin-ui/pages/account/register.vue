<script>
import {
    authFackMethods,
    notificationMethods
} from "~/store/helpers";
import {
    mapState
} from "vuex";

import {
    required,
    email
} from "vuelidate/lib/validators";

/**
 * Register component
 */
export default {
    layout: "auth",
    head() {
        return {
            title: `${this.title} | Admin Dashboard`,
        };
    },
    data() {
        return {
            user: {
                username: "",
                email: "",
                password: "",
            },
            submitted: false,
            tryingToRegister: false,
            isRegisterError: false,
            title: "Register",
        };
    },
    validations: {
        user: {
            username: {
                required,
            },
            email: {
                required,
                email,
            },
            password: {
                required,
            },
        },
    },
    computed: {
        ...mapState("modules/authfack", ["status"]),
        notification() {
            return this.$store ? this.$store.state.modules.notification : null;
        },
    },
    methods: {
        ...authFackMethods,
        ...notificationMethods,
        // Try to register the user in with the email, username
        // and password they provided.
        tryToRegisterIn() {
            this.submitted = true;
            // stop here if form is invalid
            this.$v.$touch();

            if (this.$v.$invalid) {
                return;
            } else {
                const {
                    email,
                    username,
                    password
                } = this.user;
                if (email && username && password) {
                    this.registeruser(this.user);
                }
            }
        },
    },
};
</script>

<template>
<div>
    <div class="home-btn d-none d-sm-block">
        <nuxt-link to="/" class="text-dark">
            <i class="mdi mdi-home-variant h2"></i>
        </nuxt-link>
    </div>
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <nuxt-link to="/" class="mb-5 d-block auth-logo">
                            <img src="~/assets/images/logo-dark.png" alt class="logo logo-dark" />
                        </nuxt-link>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Register Account</h5>
                                <p class="text-muted">Request for an admin account</p>
                            </div>
                            <div class="p-2 mt-4">
                                <div v-if="notification.message" :class="'alert ' + notification.type">{{ notification.message }}</div>

                                <b-form @submit.prevent="tryToRegisterIn">
                                    <b-form-group id="email-group" label="Username" label-for="username">
                                        <b-form-input id="username" v-model="user.username" type="text" placeholder="Enter username" :class="{
                          'is-invalid': submitted && $v.user.username.$error
                        }"></b-form-input>
                                        <div v-if="submitted && !$v.user.username.required" class="invalid-feedback">Username is required.</div>
                                    </b-form-group>

                                    <b-form-group id="fullname-group" label="Email" label-for="email">
                                        <b-form-input id="email" v-model="user.email" type="email" placeholder="Enter email" :class="{
                          'is-invalid': submitted && $v.user.email.$error
                        }"></b-form-input>
                                        <div v-if="submitted && $v.user.email.$error" class="invalid-feedback">
                                            <span v-if="!$v.user.email.required">Email is required.</span>
                                            <span v-if="!$v.user.email.email">Please enter valid email.</span>
                                        </div>
                                    </b-form-group>

                                    <b-form-group id="password-group" label="Password" label-for="password">
                                        <b-form-input id="password" v-model="user.password" type="password" placeholder="Enter password" :class="{
                          'is-invalid': submitted && $v.user.password.$error
                        }"></b-form-input>
                                        <div v-if="submitted && !$v.user.password.required" class="invalid-feedback">Password is required.</div>
                                    </b-form-group>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="auth-terms-condition-check" />
                                        <label class="custom-control-label" for="auth-terms-condition-check">
                                            I accept
                                            <a href="javascript: void(0);" class="text-dark">Terms and Conditions</a>
                                        </label>
                                    </div>
                                    <div class="mt-3 text-right">
                                        <b-button type="submit" variant="primary" class="w-sm">Register</b-button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="text-muted mb-0">
                                            Already have an account ?
                                            <nuxt-link to="/account/login" class="font-weight-medium text-primary">Login</nuxt-link>
                                        </p>
                                    </div>
                                </b-form>
                            </div>
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <div class="mt-5 text-center">
                        <p>
                            © {{ new Date().getFullYear()}} Craydel.
                        </p>
                    </div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
</template>

<style lang="scss" module></style>
