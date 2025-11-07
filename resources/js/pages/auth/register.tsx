import { login } from '@/routes';

import { store } from '@/actions/App/Http/Controllers/Auth/RegisterController';
import { Form, Head } from '@inertiajs/react';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/auth-layout';
import InputWithError from '@/components/input-with-error';
import PasswordInputWithError from '@/components/password-input-with-error';

export default function Register() {
    return (
        <AuthLayout
            title="Create an account"
            description="Enter your details below to create your account"
        >
            <Head title="Register" />
            <Form
                {...store.form()}
                resetOnSuccess={['password', 'password_confirmation']}
                disableWhileProcessing
                className="flex flex-col gap-6"
            >
                {({ processing }) => (
                    <>
                        <div className="grid gap-6">
                            <div className="grid gap-2">
                                <InputWithError
                                    label="First Name"
                                    name="first_name"
                                    type="text"
                                    required
                                    autoFocus
                                    tabIndex={1}
                                    autoComplete="first_name"
                                    placeholder="John"
                                />
                            </div>

                            <div className="grid gap-2">
                                <InputWithError
                                    label="Last Name"
                                    name="last_name"
                                    type="test"
                                    required
                                    tabIndex={2}
                                    autoComplete="last_name"
                                    placeholder="Doe"
                                />
                            </div>

                            <div className="grid gap-2">
                                <InputWithError
                                    label="Email Address"
                                    name="email"
                                    type="email"
                                    required
                                    tabIndex={3}
                                    autoComplete="email"
                                    placeholder="email@example.com"
                                />
                            </div>

                            <div className="grid gap-2">
                                <PasswordInputWithError
                                    label="Password"
                                    name="password"
                                    forgotPassword={false}
                                    required
                                    tabIndex={4}
                                    autoComplete="password"
                                />
                            </div>

                            <div className="grid gap-2">
                                <PasswordInputWithError
                                    label="Confirm Password"
                                    name="password_confirmation"
                                    forgotPassword={false}
                                    required
                                    tabIndex={5}
                                    autoComplete="password_confirmation"
                                />
                            </div>

                            <Button
                                type="submit"
                                className="mt-2 w-full"
                                tabIndex={5}
                                data-test="register-user-button"
                            >
                                {processing && <Spinner />}
                                Create account
                            </Button>
                        </div>

                        <div className="text-center text-sm text-muted-foreground">
                            Already have an account?{' '}
                            <TextLink href={login()} tabIndex={6}>
                                Log in
                            </TextLink>
                        </div>
                    </>
                )}
            </Form>
        </AuthLayout>
    );
}
