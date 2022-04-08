<template>
    <div class="container">
        <div class="columns is-multiline">
            <div class="card column is-two-thirds">
                <header class="card-header">
                    <p class="card-header-title">
                        Chat
                    </p>
                </header>
                <div class="card-content">
                    <div class="content">
                        <div class="chat-window" ref="messages">
                            <div class="is-spacer"></div>
                            <message v-for="msg in messages" :name="msg.name" :message="msg.message"
                                     :key="msg.id" :created_at="msg.created_at"></message>
                            <div class="is-divider" :data-content="typingText" v-if="isTyping"></div>
                            <div class="is-spacer" v-if="!isTyping"></div>
                        </div>
                        <message-form></message-form>
                    </div>
                </div>
            </div>
            <div class="column">
                <user-list></user-list>
            </div>
        </div>
        <private-chat v-if="private_chat_active" :user="private_chat_user"></private-chat>
    </div>
</template>

<script>
    import PrivateChat from "./PrivateChat";
    import UserList from "./UserList";
    import MessageForm from "./MessageForm";
    import {EventBus} from "../event-bus.js";
    import Message from "./Message";

    export default {
        name: "chat",
        components: {Message, MessageForm, UserList, PrivateChat},
        data() {
            return {
                messages: [],
                private_chat_user: '',
                private_chat_active: false,
                typing_user: [],
                typing_timers: []
            }
        },
        methods: {
            joinMainChat() {
                Echo.join('main.chat')
                    .listen('MainChatMessage', (data) => {
                        this.addMessage(data);

                        this.typing_user = _.filter(this.typing_user, function (u){
                            return data.user.id !== u.id
                        });
                    })
                    .here(users => {
                        EventBus.$emit('users.here', users);
                    })
                    .joining(user => {
                        EventBus.$emit('user.join', user);
                    })
                    .leaving(user => {
                        EventBus.$emit('user.leave', user);
                    });
                Echo.private('main.chat')
                    .listenForWhisper('typing', (currentUser) => {
                        if (_.some(this.typing_user, currentUser)){
                            clearTimeout(this.typing_timers[currentUser.id]);
                            this.typing_timers[currentUser.id] = setTimeout(() => {
                                this.typing_user = _.filter(this.typing_user, function (u) {
                                    return u.id !== currentUser.id;
                                });
                            }, 800);
                        } else
                            this.typing_user.push(currentUser);
                    });
            },

            listenInternalEvents() {
                EventBus.$on('add.message', (message) => {
                    this.addMessage(message);
                });
                EventBus.$on('open-personal-chat', function (user) {
                    this.private_chat_active = true;
                    this.private_chat_user = user;
                }.bind(this));
                EventBus.$on('close-personal-chat', function () {
                    this.private_chat_active = false;
                    this.private_chat_user = null;
                }.bind(this));
                EventBus.$on('user.join', (user) => {
                    this.messages.push({
                        id: Math.floor(Math.random() * 10000),
                        name: user.name + " joined",
                        message: "",
                        created_at: new Date()
                    });
                });
                EventBus.$on('user.leave', (user) => {
                    this.messages.push({
                        id: Math.floor(Math.random() * 10000),
                        name: user.name + " leave",
                        message: "",
                        created_at: new Date()
                    });
                });
            },

            addMessage(data) {
                this.messages.push({
                    id: data.message.id,
                    name: data.user.name,
                    message: data.message.body,
                    created_at: data.message.created_at
                });
            }
        },
        updated() {
            this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight;
        },
        created() {
            axios.get('/messages')
                .then(function (response) {
                    this.messages = _.map(response.data, (data) => {
                        return {
                            id: data.id,
                            name: data.user.name,
                            message: data.body,
                            created_at: data.created_at
                        }
                    });
                }.bind(this))
                .catch(function (error) {
                    console.log(error);
                });

            this.joinMainChat();

            this.listenInternalEvents();
        },
        computed: {
            isTyping() {
                return this.typing_user.length > 0;
            },
            typingText() {
                if (this.typing_user.length === 1)
                    return _.map(this.typing_user, usr => usr.name).join('').concat(' is typing');

                return _.map(this.typing_user, usr => usr.name).join(', ').concat(' are typing');
            }
        },
        watch: {
            typing_user(new_users, old_users){
                let currentUser = null;

                if(new_users.length > old_users.length){
                    currentUser = _.differenceWith(new_users, old_users, _.isEqual).shift();

                    this.typing_timers[currentUser.id] = setTimeout(() => {
                        this.typing_user = _.filter(this.typing_user, function (u) {
                            return u.id !== currentUser.id;
                        });
                    }, 800);
                } else if (new_users.length < old_users.length){
                    currentUser = _.differenceWith(old_users, new_users, _.isEqual).shift();

                    clearTimeout(this.typing_timers[currentUser.id]);
                    delete this.typing_timers[currentUser.id];
                }
            }
        },
        beforeDestroy() {
            Echo.leave('main.chat');
        }
    }
</script>

<style scoped>

</style>
