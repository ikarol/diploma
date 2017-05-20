<template>
    <div>
        <div class="panel-heading">
            <slot name="task-title"></slot>
        </div>
        <div class="panel-body">
            <slot name="request-student"></slot>
            <slot name="request-status"></slot>
            <slot name="request-message"></slot>
            <slot name="request-cr_at"></slot>
        </div>
        <slot name="request-footer"></slot>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('request panel mounted');
        }
    }
</script>
