export function formatPrice(amount, locale = 'vi-VN', currency = 'VND') {
    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: currency,
    }).format(amount);
}
