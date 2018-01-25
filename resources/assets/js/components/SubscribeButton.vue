<template>
    <div>
        <button class="btn" :class="classes" @click="subscribe">Subscribe</button>
    </div>
</template>

<script>
export default {
    props: ['active'],
    computed: {
        classes() {
            return ['btn', this.active ? 'btn-primary' : 'btn-default'];
        }
    },
    methods: {
        subscribe() {
            axios.post(location.pathname + '/subscriptions');
            this.active = true;
            flash('Subscribed');
        }
    },
}
</script>