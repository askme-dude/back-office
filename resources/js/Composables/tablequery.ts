import { computed, Ref, UnwrapRef } from "vue";

type QueryParams = {
    keyword: Ref<UnwrapRef<string>>;
    filterBy: Ref<
        UnwrapRef<{ label: string; column: string; cell?: Function }>
    >;
    sortBy: Ref<UnwrapRef<string>>;
    perPage: Ref<UnwrapRef<number>>;
};

export function useQuery({ keyword, filterBy, sortBy, perPage }: QueryParams) {
    return computed(() => {
        return {
            ...(keyword.value && {
                filter: {
                    [filterBy.value.column]: keyword.value,
                },
            }),
            ...(sortBy.value && { sort: sortBy.value }),
            ...(perPage.value && { per_page: perPage.value }),
        };
    });
}
