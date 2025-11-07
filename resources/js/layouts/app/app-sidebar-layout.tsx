import { AppContent } from '@/components/app-content';
import { AppShell } from '@/components/app-shell';
import { AppSidebar } from '@/components/app-sidebar';
import { AppSidebarHeader } from '@/components/app-sidebar-header';
import { type BreadcrumbItem } from '@/types';
import { type PropsWithChildren, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { toast, Toaster } from 'sonner';
import { Flash } from '@/types/common';

export default function AppSidebarLayout({
    children,
    breadcrumbs = [],
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
        <AppShell variant="sidebar">
            <AppSidebar />
            <AppContent variant="sidebar" className="overflow-x-hidden">
                <AppSidebarHeader breadcrumbs={breadcrumbs} />
                <Toaster position="top-right" expand={true} richColors />
                {children}
            </AppContent>
        </AppShell>
    );
}
