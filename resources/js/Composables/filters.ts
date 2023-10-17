export function useLocaleDateTime(date: Date): string {
    return date.toLocaleString("id", {
        timeZone: "Asia/Jakarta",
        timeStyle: "long",
        dateStyle: "medium",
    });
}

export function useLocaleDate(date: Date): string {
    return date.toLocaleDateString("id", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
}

export function useLongLocalDate(date: Date): string {
    return date.toLocaleDateString("id", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
}

export function useLocalCurrency(number: number): string {
    return new Intl.NumberFormat("id", {
        style: "currency",
        currency: "IDR",
    }).format(number);
}
