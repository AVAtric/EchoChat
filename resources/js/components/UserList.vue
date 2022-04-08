<template>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                Online users
            </p>
        </header>
        <div class="card-content">
            <div class="content">
                <div class="list is-hoverable" v-if="not_empty">
                    <user v-for="user in users" :name="user.name" :id="user.id" :key="user.id"></user>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import User from "./User";
    import {EventBus} from "../event-bus.js";

    export default {
        name: "user-list",
        components: {
            'user': User
        },
        data() {
            return {
                users: []
            }
        },
        mounted() {
            EventBus.$on('users.here', (users) => {
                this.users = _.remove(users, function (user) {
                    return user.id !== Laravel.user.id;
                });
            });
            EventBus.$on('user.join', (user) => {
                if (user.id !== Laravel.user.id)
                    this.users.unshift(user);
            });
            EventBus.$on('user.leave', (user) => {
                if (user.id !== Laravel.user.id)
                    this.users = this.users.filter(u => {
                        return u.id !== user.id
                    })
            });
        },
        computed:{
            not_empty() {
                return this.users.length > 0;
            }
        }
    }
</script>

<style scoped>

</style>
