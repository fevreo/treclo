<template>
    <div>
        <header>
            <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
                <div class="container">
                    <router-link :to="{ name: 'home' }" class="navbar-brand text-white">TreClo</router-link>
                    <div id="navbarSupporttedContent">
                        <!-- Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <router-link :to="{ name: 'login' }" class="nav-link text-white" v-if="!isLoggedIn">
                                Login
                            </router-link>
                            <router-link :to="{ name: 'register' }" class="nav-link text-white" v-if="!isLoggedIn">
                                Register
                            </router-link>
                            <router-link :to="{ name: 'board' }" class="nav-link text-white" v-if="isLoggedIn">
                                Tasks
                            </router-link>
                            <li class="nav-link text-white" v-if="isLoggedIn">
                                Hi, {{ name }}
                            </li>
                            <li class="nav-link" v-if="isLoggedIn">
                                <button type="button" class="btn btn-outline-danger btn-sm" @click="logout">
                                    Logout
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main class="py-5">
            <router-view></router-view>
        </main>
        <footer class="mt-5 py-5 bg-secondary rounded-top">
            <div class="text-center text-white">
                Copyright © 2022 by Y.K. All rights reserved.
            </div>
        </footer>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoggedIn: null,
            name: null,
        };
    },
    methods: {
        logout() {
            axios
                .post("api/logout", {
                    id: localStorage.getItem("user_id")
                })
                .catch(function (error) {
                    console.error(error);
                });
            localStorage.removeItem("jwt");
            localStorage.removeItem("user");
            localStorage.removeItem("user_id");

            if (localStorage.getItem("jwt") != null) {
                this.$router.go("/");
            } else {
                this.$router.go("/login");
            }
        },
    },
    mounted() {
        this.isLoggedIn = localStorage.getItem("jwt");
        this.name = localStorage.getItem("user");
    },
};
</script>