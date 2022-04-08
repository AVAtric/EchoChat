<template>
    <input type="hidden" name="captcha" id="captcha" v-model="token">
</template>

<script>
    export default {
        name: "CaptchaToken",
        props: [
            "captcha_name"
        ],
        data() {
            return {
                token: ''
            }
        },
        mounted() {
            this.init_captcha();
        },
        methods: {
            async init_captcha() {
                while (typeof this.$recaptcha !== "function")
                    await this.sleep(100);

                this.$recaptcha(this.$props.captcha_name).then(function (token) {
                    this.token = token;
                }.bind(this));
            },
            sleep(milliseconds) {
                return new Promise(resolve => setTimeout(resolve, milliseconds));
            }
        }
    }
</script>

<style scoped>

</style>
