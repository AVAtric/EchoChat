<template>
    <a class="list-item" v-bind:id="id" v-on:click="personal">
        <div class="columns is-mobile">
            <div class="column is-narrow">{{name}}</div>
            <div v-if="new_message" class="column has-text-right"><span class="tag is-rounded is-danger is-normal">New Message</span></div>
        </div>
    </a>
</template>

<script>
    import {EventBus} from "../event-bus.js";

    export default {
        name: "user",
        data () {
          return {
              new_message: false
          }
        },
        props: ['name', 'id'],
        mounted() {
            Echo.private('notifications.' + Laravel.user.id)
                .listen('NotificationMessage', function (data) {
                   if(data.sender === this.$props.id){
                       this.new_message = true;
                   }
                }.bind(this));
        },
        beforeDestroy() {
            Echo.leave('notifications.' + Laravel.user.id);
        },
        methods: {
            personal() {
                this.new_message = false;
                EventBus.$emit('open-personal-chat', {id: this.$props.id, name: this.$props.name});
            }
        }
    }
</script>

<style scoped>

</style>
