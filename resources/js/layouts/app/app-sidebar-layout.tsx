import { AppSidebar } from '@/components/app-sidebar';
import { type BreadcrumbItem } from '@/types';
import { type PropsWithChildren, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { toast, Toaster } from 'sonner';
import { Flash } from '@/types/common';
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';
import Header from '@/components/header';

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
        <SidebarProvider>
            <AppSidebar />
            <SidebarInset>
                <Header breadcrumbs={breadcrumbs} />
                <Toaster position="top-right" expand={true} richColors />
                <div className="mt-3 mr-2 ml-2 md:ml-0">{children}</div>
            </SidebarInset>
        </SidebarProvider>
    );
}
