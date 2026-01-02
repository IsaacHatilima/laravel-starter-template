import { AppSidebar } from '@/components/app-sidebar';
import Header from '@/components/header';
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';
import { type BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/react';
import { type PropsWithChildren, useEffect, useRef } from 'react';
import { toast, Toaster } from 'sonner';

type FlashStatus = 'success' | 'info' | 'warning' | 'error';

type Flash = {
    status?: FlashStatus;
    message?: string;
    success?: string;
    info?: string;
    warning?: string;
    error?: string;
};

export default function AppSidebarLayout({
    children,
    breadcrumbs = [],
}: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[] }>) {
    const page = usePage();
    console.log(page.props);

    const flash = page.flash as Flash | undefined;
    const { socialiteToast } = page.props as {
        socialiteToast?: Flash;
    };

    const lastFlash = useRef<string | null>(null);

    useEffect(() => {
        if (flash?.status && flash.message) {
            const payload = {
                type: flash.status,
                message: flash.message,
            };

            const serialized = JSON.stringify(payload);
            if (serialized === lastFlash.current) return;
            lastFlash.current = serialized;

            toast[flash.status](flash.message);
            return;
        }

        if (!socialiteToast) return;

        const type = (['success', 'info', 'warning', 'error'] as const).find(
            (key) => socialiteToast[key],
        );

        if (!type) return;

        const payload = {
            type,
            message: socialiteToast[type],
        };

        const serialized = JSON.stringify(payload);
        if (serialized === lastFlash.current) return;
        lastFlash.current = serialized;

        toast[type](socialiteToast[type] as string);
    }, [flash, socialiteToast]);
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
