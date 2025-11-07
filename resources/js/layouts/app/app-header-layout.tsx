import { AppContent } from '@/components/app-content';
import { AppHeader } from '@/components/app-header';
import { AppShell } from '@/components/app-shell';
import { type BreadcrumbItem } from '@/types';
import { PropsWithChildren, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { Flash } from '@/types/common';
import { toast, Toaster } from 'sonner';

export default function AppHeaderLayout({
    children,
    breadcrumbs,
}: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[] }>) {
    const pageProps = usePage().props;
    const flash = pageProps.flash as Flash;
    useEffect(() => {
        (['success', 'info', 'warning', 'error'] as const).forEach((type) => {
            const message = flash?.[type];
            if (message) {
                toast[type](type.charAt(0).toUpperCase() + type.slice(1), {
                    description: message,
                });
            }
        });
    }, [flash]);
    return (
        <AppShell>
            <AppHeader breadcrumbs={breadcrumbs} />
            <Toaster position="top-right" expand={true} richColors />
            <AppContent>{children}</AppContent>
        </AppShell>
    );
}
