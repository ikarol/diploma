<template>
    <tr>
        <td><slot name="col-topic"></slot></td>
        <td><slot name="col-requests"></slot></td>
        <td><slot name="col-cr_at"></slot></td>
        <td><slot name="col-actions"></slot></td>
    </tr>
</template>

<script>
    export default {
        mounted() {
            console.log('task row mounted');
        }
    }
</script>
