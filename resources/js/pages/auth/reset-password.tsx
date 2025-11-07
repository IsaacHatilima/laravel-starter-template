import { update } from '@/routes/password';
import { Form, Head } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/auth-layout';
import PasswordInputWithError from '@/components/password-input-with-error';

interface ResetPasswordProps {
    token: string;
    email: string;
}

export default function ResetPassword({ token, email }: ResetPasswordProps) {
    return (
        <AuthLayout
            title="Reset password"
            description="Please enter your new password below"
        >
            <Head title="Reset password" />

            <Form
                {...update.form()}
                transform={(data) => ({ ...data, token, email })}
                resetOnSuccess={['password', 'password_confirmation']}
            >
                {({ processing }) => (
                    <div className="grid gap-6">
                        <div className="grid gap-2">
                            <PasswordInputWithError
                                label="Password"
                                name="password"
                                forgotPassword={false}
                                required
                                tabIndex={1}
                                autoComplete="password"
                            />
                        </div>

                        <div className="grid gap-2">
                            <PasswordInputWithError
                                label="Confirm Password"
                                name="password_confirmation"
                                forgotPassword={false}
                                required
                                tabIndex={2}
                                autoComplete="password_confirmation"
                            />
                        </div>

                        <Button
                            type="submit"
                            className="mt-4 w-full"
                            disabled={processing}
                            data-test="reset-password-button"
                        >
                            {processing && <Spinner />}
                            Reset password
                        </Button>
                    </div>
                )}
            </Form>
        </AuthLayout>
    );
}
