<template>
    <div v-if="signedIn">
        <div class="form-group">
            <wysiwyg name="body" v-model="body" placeholder="Have something to say?" :shouldClear="complete"></wysiwyg>
            <!-- <textarea name="body" id="body" class="form-control" placeholder="Have something to say?" rows="5" v-model="body" required></textarea> -->
        </div>
        <button type="submit" class="btn btn-default" @click="addReply">Post</button>
    </div>
    <p class="text-center" v-else>Please
        <a href="/login">sign in</a> to participate in this discussion.</p>
</template>

<script>

export default {
    data() {
        return {
            body: '',
            complete: false
        };
    },
    methods: {
        addReply() {
            axios.post(location.pathname + '/replies', { body: this.body })
                .then(response => {
                    this.body = '';
                    this.complete = true;

                    this.$emit('created', response.data);

                    flash('Your reply has been posted');
                })
                .catch(error => {
                    flash(error.response.data, 'danger');
                });
        }
    }
}

</script>