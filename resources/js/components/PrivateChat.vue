<template>
    <div class="modal" :class="is_active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">{{user.name}}</p>
                <button class="delete" aria-label="close" v-on:click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <div class="chat-window-small" ref="messages">
                    <div class="is-spacer"></div>
                    <message v-for="msg in messages" :name="msg.name" :message="msg.message" :key="msg.id" :created_at="msg.created_at"></message>
                    <div class="is-divider" :data-content="typing_message" v-if="isTyping"></div>
                    <div class="is-spacer" v-if="!isTyping"></div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="columns is-fullwidth">
                    <div class="column is-four-fifths">
                        <form v-on:submit.prevent="send">
                            <input class="input" v-model="message" type="text" :placeholder="quote"
                                   v-on:keyup="typing">
                        </form>
                    </div>
                    <div class="column">
                        <a class="button is-fullwidth is-primary" v-on:click="send">Send</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../event-bus.js";
    import Message from "./Message";

    export default {
        name: "private-chat",
        components: {Message},
        data: () => {
            return {
                chat_id: '',
                is_active: '',
                users: [],
                messages: [],
                message: '',
                quote: '',
                typing_message: '',
                typing_timer: false
            }
        },
        props: [
            'user'
        ],
        updated() {
            this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight;
        },
        created() {
            document.body.addEventListener('keyup', e => {
                if (e.keyCode === 27) {
                    this.closeModal();
                }
            });

            this.is_active = "is-active";

            axios.get('/quote')
                .then(function (response) {
                    this.quote = response.data;
                }.bind(this))
                .catch(function (reason) {
                    console.log(reason);
                });

            this.joinChat();
        },
        mounted() {
            EventBus.$on('close-personal-chat', function () {
                this.is_active = '';
            }.bind(this));
        },
        beforeDestroy() {
            Echo.leave('chat.' + this.chat_id);
        },
        methods: {
            joinChat(){
                axios.get('/new/chat/' + Laravel.user.id + '/' + this.$props.user.id)
                    .then(function (response) {
                        this.chat_id = response.data.id;
                        Echo.private('chat.' + response.data.id)
                            .listenForWhisper('typing', function (e) {
                                this.typing_message = e.name + ' is typing...';

                                if (this.typing_timer) clearTimeout(this.typing_timer);

                                this.typing_timer = setTimeout(function () {
                                    this.typing_message = '';
                                }.bind(this), 800);
                            }.bind(this));
                        this.messages = _.map(response.data.messages, (data) => {
                            return {
                                id: data.id,
                                name: data.user.name,
                                message: data.body,
                                created_at: data.created_at
                            }
                        });
                        Echo.join('chat.' + this.chat_id)
                            .here(users => {
                                this.users = users;
                            })
                            .joining(user => {
                                this.users.unshift(user);
                                this.messages.push({
                                    id: Math.floor(Math.random() * 10000),
                                    name: user.name + " joined",
                                    message: "",
                                    created_at: new Date()
                                });
                            })
                            .leaving(user => {
                                this.users = this.users.filter(u => {
                                    return u.id !== user.id
                                });
                                this.messages.push({
                                    id: Math.floor(Math.random() * 10000),
                                    name: user.name + " leave",
                                    message: "",
                                    created_at: new Date()
                                });
                            })
                            .listen('PersonalChatMessage', function (data) {
                                this.typing_message = '';
                                clearTimeout(this.typing_timer);
                                this.messages.push({
                                    id: data.message.id,
                                    name: data.user.name,
                                    message: data.message.body,
                                    created_at: data.created_at
                                });
                            }.bind(this));
                    }.bind(this))
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            closeModal() {
                EventBus.$emit('close-personal-chat', this.$props.user);
            },
            send() {
                if (this.message !== '') {
                    if (this.users.length === 1) {
                        axios.get('/notification/' + this.$props.user.id,)
                            .catch(function (error) {
                                console.log(error);
                            });
                    }
                    axios.post('/chat/' + this.chat_id, {
                        message: this.message
                    })
                        .then((response) => {
                            this.messages.push({
                                id: response.data.id,
                                name: response.data.user.name,
                                message: response.data.body,
                                created_at: response.data.created_at
                            });
                        }).catch(function (error) {
                        console.log(error);
                    });
                }
                this.message = '';
            },
            typing(e) {
                if (e.keyCode !== 27 && e.keyCode !== 13)
                    Echo.private('chat.' + this.chat_id)
                        .whisper('typing', {
                            name: Laravel.user.name
                        });
            },
        },
        computed: {
            isTyping() {
                return this.typing_message !== '';
            }
        }
    }
</script>

<style scoped>

</style>
