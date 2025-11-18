import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { destroy, redirectToGoogle } from '@/routes/connected-accounts';
import { edit } from '@/routes/profile';
import type { BreadcrumbItem, ConnectedAccount } from '@/types';
import { Head, router, usePage } from '@inertiajs/react';
import { FaGoogle } from 'react-icons/fa';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Connected Accounts',
        href: edit().url,
    },
];
export default function ConnectedAccounts() {
    const connectedAccounts = usePage().props
        .connectedAccounts as ConnectedAccount[];

    const handleDisconnect = (id: string) => {
        router.delete(destroy.url(id));
    };

    const services = [
        {
            title: ' Connection',
            url: redirectToGoogle.url(),
            icon: <FaGoogle size={25} />,
            service: 'google',
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Connected Accounts" />
            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title="Connected Accounts"
                        description="List of connected auth accounts"
                    />

                    <Card className="w-full">
                        <form className="w-full space-y-6">
                            <CardContent className="flex justify-center">
                                <div className="grid w-full grid-cols-1 gap-4">
                                    {services.map((connection) => {
                                        const matched = connectedAccounts.find(
                                            (acc) =>
                                                acc.service ===
                                                connection.service,
                                        );

                                        return (
                                            <div
                                                key={connection.service}
                                                className="flex items-center justify-between rounded border p-4"
                                            >
                                                <div className="flex items-center gap-2">
                                                    {connection.icon}
                                                    <span>
                                                        {connection.title}
                                                    </span>
                                                </div>

                                                {matched ? (
                                                    <Button
                                                        variant="destructive"
                                                        onClick={() =>
                                                            handleDisconnect(
                                                                matched.id,
                                                            )
                                                        }
                                                        type="button"
                                                    >
                                                        Disconnect
                                                    </Button>
                                                ) : (
                                                    <Button
                                                        variant="outline"
                                                        onClick={() =>
                                                            (window.location.href =
                                                                connection.url)
                                                        }
                                                        type="button"
                                                    >
                                                        Connect
                                                    </Button>
                                                )}
                                            </div>
                                        );
                                    })}
                                </div>
                            </CardContent>
                        </form>
                    </Card>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
