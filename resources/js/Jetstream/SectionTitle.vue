<template>
    <div class="md:col-span-1 flex justify-between">
        <div>
            <h3 class="text-lg font-medium text-gray-900">
                <slot name="title"></slot>
            </h3>
            <p class="mt-1 text-sm text-gray-600">
                <slot name="description"></slot>
            </p>
        </div>
        <div>
            <slot name="aside"></slot>
        </div>
    </div>
</template>
