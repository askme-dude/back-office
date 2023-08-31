<script setup>
defineProps({
    links:Array,
    paginate:Number,
})
</script>

<template>
    <div class="join flex justify-end">
        <Component
            :is="link.url ? 'a' : 'span'"
            v-for="link in links"
            :href="link.url + '&paginate=' + 10"
            v-html="link.label"
            class="btn join-item btn-xs"
            :class="{
                    'btn-disabled': !link.url,
                    'btn-active': link.active,
                }"
        />
    </div>
</template>
