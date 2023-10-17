export function useNoPhotoUrl(): string {
    const baseUrl = window.location.origin;

    return baseUrl + "/assets/noPhotoFound.png";
}
