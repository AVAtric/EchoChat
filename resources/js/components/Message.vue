<template>
    <div class="box">
        <article class="media">
            <div class="media-content">
                <div class="content break-word">
                    <p>
                        <strong>{{name}}</strong> <small><time-ago locale="en" :datetime="created_at" long refresh></time-ago></small>
                        <br>
                        {{message}}
                    </p>
                </div>
            </div>
        </article>
    </div>
</template>

<script>
    import TimeAgo from 'vue2-timeago';

    export default {
        name: "message",
        props: ["name", "message", "created_at"],
        components: {TimeAgo}
    }
</script>

<style scoped>

</style>
