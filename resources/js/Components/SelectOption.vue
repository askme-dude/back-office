<script setup>
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
    TransitionRoot,
} from "@headlessui/vue";
import { CheckIcon, ChevronDownIcon } from "@heroicons/vue/24/outline/index.js";
import { computed, ref } from "vue";
import { debounce } from "lodash";

const props = defineProps({
    modelValue: {
        type: [Object, String],
        required: true,
        default: (raw) => raw || {},
    },
    options: { type: Array, required: true },
    label: { type: String, default: "nama" },
    placeholder: { type: String, default: "Pilih" },
    error: { type: Boolean, default: false },
});

const emit = defineEmits(["update:modelValue", "search"]);

const query = ref("");

const fireEvent = debounce((value) => emit("search", value), 200);

const filteredOptions = computed(() => {
    return query.value === ""
        ? props.options
        : props.options.filter((option) => {
              return option[props.label]
                  .toLowerCase()
                  .includes(query.value.toLowerCase());
          });
});
</script>

<template>
    <Combobox
        :model-value="modelValue"
        @update:modelValue="(val) => $emit('update:modelValue', val)"
        by="id"
    >
        <div class="relative">
            <div
                class="relative w-full cursor-default overflow-hidden rounded-lg border bg-white text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 sm:text-sm"
                :class="error ? 'border-error' : 'border-gray-400'"
            >
                <ComboboxInput
                    class="input w-full text-sm leading-5 text-gray-900"
                    :displayValue="
                        (option) =>
                            typeof option === 'string' ? option : option[label]
                    "
                    @change="query = $event.target.value"
                    @keyup="fireEvent($event.target.value)"
                    :placeholder="placeholder"
                />
                <ComboboxButton
                    class="absolute inset-y-0 right-0 flex items-center pr-2"
                >
                    <ChevronDownIcon
                        class="h-5 w-5 text-gray-400"
                        aria-hidden="true"
                    />
                </ComboboxButton>
            </div>
            <TransitionRoot
                leave="transition ease-in duration-100"
                leaveFrom="opacity-100"
                leaveTo="opacity-0"
                @after-leave="query = ''"
            >
                <ComboboxOptions
                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-base-100 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                >
                    <div
                        v-if="filteredOptions.length === 0 && query !== ''"
                        class="relative cursor-default select-none px-4 py-2 text-sm text-gray-700"
                    >
                        Tidak ada data
                    </div>

                    <ComboboxOption
                        v-for="option in filteredOptions"
                        as="template"
                        :key="option.id"
                        :value="option"
                        v-slot="{ selected, active }"
                    >
                        <li
                            class="relative cursor-default select-none py-2 pl-10 pr-4 text-sm"
                            :class="{
                                'bg-primary text-white': active,
                                'text-gray-900': !active,
                            }"
                        >
                            <span
                                class="block truncate"
                                :class="{
                                    'font-medium': selected,
                                    'font-normal': !selected,
                                }"
                            >
                                {{ option[label] }}
                            </span>
                            <span
                                v-if="selected"
                                class="absolute inset-y-0 left-0 flex items-center pl-3"
                                :class="{
                                    'text-white': active,
                                    'text-primary': !active,
                                }"
                            >
                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                            </span>
                        </li>
                    </ComboboxOption>
                </ComboboxOptions>
            </TransitionRoot>
        </div>
    </Combobox>
</template>
