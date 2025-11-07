import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { type BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/react';
import { useRef } from 'react';

import HeadingSmall from '@/components/heading-small';
import PasswordInputWithError from '@/components/password-input-with-error';
import { Button } from '@/components/ui/button';
import { edit } from '@/routes/user-password';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Password settings',
        href: edit().url,
    },
];

export default function Password() {
    const passwordInput = useRef<HTMLInputElement>(null);
    const currentPasswordInput = useRef<HTMLInputElement>(null);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Password settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title="Update password"
                        description="Ensure your account is using a long, random password to stay secure"
                    />

                    <Form
                        {...PasswordController.update.form()}
                        options={{
                            preserveScroll: true,
                        }}
                        resetOnError={[
                            'password',
                            'password_confirmation',
                            'current_password',
                        ]}
                        resetOnSuccess
                        onError={(errors) => {
                            if (errors.password) {
                                passwordInput.current?.focus();
                            }

                            if (errors.current_password) {
                                currentPasswordInput.current?.focus();
                            }
                        }}
                        className="space-y-6"
                    >
                        {({ processing }) => (
                            <>
                                <div className="grid gap-2">
                                    <PasswordInputWithError
                                        label="Current Password"
                                        name="current_password"
                                        forgotPassword={false}
                                        required
                                        autoFocus
                                        tabIndex={1}
                                        autoComplete="current_password"
                                    />
                                </div>

                                <div className="grid gap-2">
                                    <PasswordInputWithError
                                        label="Password"
                                        name="password"
                                        forgotPassword={false}
                                        required
                                        tabIndex={2}
                                        autoComplete="password"
                                    />
                                </div>

                                <div className="grid gap-2">
                                    <PasswordInputWithError
                                        label="Confirm Password"
                                        name="password_confirmation"
                                        forgotPassword={false}
                                        required
                                        tabIndex={3}
                                        autoComplete="password_confirmation"
                                    />
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button
                                        disabled={processing}
                                        data-test="update-password-button"
                                    >
                                        Save password
                                    </Button>
                                </div>
                            </>
                        )}
                    </Form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
