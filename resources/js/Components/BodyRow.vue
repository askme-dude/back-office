<script setup lang="ts">
type Column = { label: string; column: string; cell?: Function };

defineProps<{
    content: Column;
    data: object;
}>();
</script>

<template>
    <td>
        <template v-if="content.cell">
            {{ content.cell(data[content.column]) }}
        </template>
        <template v-else>
            {{ data[content.column] }}
        </template>
    </td>
</template>
