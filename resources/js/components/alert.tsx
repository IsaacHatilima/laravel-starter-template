import { Flash } from '@/types/common';
import { capitalize } from '@/utils/capitalise';
import { usePage } from '@inertiajs/react';

const colorMap = {
    info: {
        border: 'border-blue-300 dark:border-blue-800',
        bg: 'bg-blue-50 dark:bg-gray-800',
        text: 'text-blue-600 dark:text-blue-400',
    },
    error: {
        border: 'border-red-300 dark:border-red-800',
        bg: 'bg-red-50 dark:bg-gray-800',
        text: 'text-red-600 dark:text-red-400',
    },
    success: {
        border: 'border-green-300 dark:border-green-800',
        bg: 'bg-green-50 dark:bg-gray-800',
        text: 'text-green-600 dark:text-green-400',
    },
    warning: {
        border: 'border-yellow-300 dark:border-yellow-800',
        bg: 'bg-yellow-50 dark:bg-gray-800',
        text: 'text-yellow-600 dark:text-yellow-400',
    },
} as const;

function Alert() {
    const pageProps = usePage().props;
    const flash = pageProps.flash as Flash;

    const type = (['success', 'info', 'warning', 'error'] as const).find(
        (key) => flash[key],
    );
    const message = type ? flash[type] : null;

    const classes = type ? colorMap[type] : null;

    if (!message || !classes) return null;

    return (
        <div
            id="alert-border-1"
            className={`mb-4 flex items-center border-t-4 p-4 text-sm font-medium ${classes.border} ${classes.bg} ${classes.text}`}
            role="alert"
        >
            <div className="flex flex-col items-start gap-2">
                <div className="font-bold">{type && capitalize(type)}</div>
                <div>{message}</div>
            </div>
        </div>
    );
}

export default Alert;
