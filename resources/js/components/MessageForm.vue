<template>
    <div class="columns">
        <div class="column is-four-fifths">
            <form v-on:submit.prevent="send">
                <input class="input" type="text" v-model="message" :placeholder="quote" v-on:keyup.prevent="typing">
            </form>
        </div>
        <div class="column">
            <a class="button is-fullwidth is-primary" v-on:click="send">Send</a>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../event-bus";

    export default {
        name: "message-form",
        data() {
            return {
                message: '',
                quote: Laravel.quote
            }
        },
        methods: {
            send() {
                if (this.message !== '')
                    axios.post('/chat', {
                        'message': this.message
                    })
                        .then(function(response){
                            EventBus.$emit('add.message', {
                                user: response.data.user,
                                message: response.data
                            });
                        }).catch(function (error) {
                        console.log(error);
                    });

                this.message = '';
            },
            typing(e) {
                if (e.keyCode !== 27 && e.keyCode !== 13)
                    Echo.private('main.chat')
                        .whisper('typing', {
                            id: Laravel.user.id,
                            name: Laravel.user.name
                        });
            },
        }
    }
</script>

<style scoped>

</style>
