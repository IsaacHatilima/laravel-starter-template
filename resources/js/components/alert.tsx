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

type FlashMap = {
    success?: string;
    info?: string;
    warning?: string;
    error?: string;
};

type StatusFlash = {
    status?: keyof FlashMap;
    message?: string;
};

export default function Alert() {
    const page = usePage();

    const flash = page.flash as (FlashMap & StatusFlash) | undefined;
    const { socialiteToast } = page.props as {
        socialiteToast?: FlashMap;
    };

    if (flash?.status && flash.message) {
        const classes = colorMap[flash.status];
        if (!classes) return null;

        return (
            <AlertBox
                type={flash.status}
                message={flash.message}
                classes={classes}
            />
        );
    }

    const source = socialiteToast ?? flash;
    if (!source) return null;

    const type = (['success', 'info', 'warning', 'error'] as const).find(
        (key) => source[key],
    );

    if (!type) return null;

    const message = source[type];
    const classes = colorMap[type];

    if (!message || !classes) return null;

    return <AlertBox type={type} message={message} classes={classes} />;
}

function AlertBox({
    type,
    message,
    classes,
}: {
    type: string;
    message: string;
    classes: { border: string; bg: string; text: string };
}) {
    return (
        <div
            className={`mb-4 flex w-full items-center border-t-4 p-4 text-sm font-medium ${classes.border} ${classes.bg} ${classes.text}`}
            role="alert"
        >
            <div className="flex flex-col items-start gap-2">
                <div className="font-bold">{capitalize(type)}</div>
                <div>{message}</div>
            </div>
        </div>
    );
}
