<template>
    <tr>
        <td><slot name="col-topic"></slot></td>
        <td><slot name="col-student"></slot></td>
        <td><slot name="col-group"></slot></td>
        <td><slot name="col-status"></slot></td>
        <td><slot name="col-cr_at"></slot></td>
        <td><slot name="col-actions"></slot></td>
    </tr>
</template>

<script>
    export default {
        mounted() {
            console.log('request row mounted');
        }
    }
</script>
