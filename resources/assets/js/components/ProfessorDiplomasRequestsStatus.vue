<template>
    <div class="btn-group">
    <button type="button" v-for="(key, val) in values" @click="changeSelectVal(val)" :class="['btn', { 'btn-primary': mutableSelected === val, 'btn-default': mutableSelected !== val }]">{{ key }}</button>
  </div>
</template>

<script>
export default {
    props: ['values','selected','default'],
    mounted () {
        this.mutableSelected = this.default;
        this.$emit('update:selected', this.default);
    },
    methods: {
        changeSelectVal: function(val) {
            this.mutableSelected = val;
            this.$emit('update:selected', val);
        }
    },
    data() {
        return {
            mutableSelected: this.selected,
        }
    }
}
</script>
