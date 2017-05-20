<template>
    <div class="btn-group">
    <button type="button" v-for="(item, index) in values"
        @click="changeSelectVal(index)"
        :class="['btn', { 'btn-primary': mutableSelected === index,
         'btn-default': mutableSelected !== index }]">{{ item }}</button>
  </div>
</template>

<script>
export default {
    props: ['values','selected','default'],
    mounted () {
        this.mutableSelected = this.default;
        this.$emit('update:selected', this.default);
        console.log(this.mutableSelected);
    },
    methods: {
        changeSelectVal: function(index) {
            this.mutableSelected = index;
            this.$emit('update:selected', index);
        }
    },
    data() {
        return {
            mutableSelected: this.selected,
        }
    }
}
</script>
