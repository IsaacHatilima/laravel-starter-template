import { usePage } from '@inertiajs/react';
import { Eye, EyeOff } from 'lucide-react';
import React, { useState } from 'react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';

interface PasswordInputWithErrorProps extends React.InputHTMLAttributes<HTMLInputElement> {
    label?: string;
    name: string;
    forgotPasswordLink?: string;
    forgotPassword: boolean;
}

export default function PasswordInputWithError({
    label,
    name,
    forgotPassword,
    forgotPasswordLink,
    className,
    ...props
}: PasswordInputWithErrorProps) {
    const { errors } = usePage().props as { errors: Record<string, string> };
    const [showPassword, setShowPassword] = useState(false);

    const error = errors[name];

    return (
        <div>
            <div className="mb-1 flex items-center">
                {label && <Label htmlFor={name}>{label}</Label>}
                {forgotPassword && (
                    <TextLink href={forgotPasswordLink ?? '#'} className="ml-auto text-sm" tabIndex={5}>
                        Forgot Password?
                    </TextLink>
                )}
            </div>

            <div className="relative">
                <input
                    id={name}
                    name={name}
                    type={showPassword ? 'text' : 'password'}
                    placeholder="••••••••"
                    className={cn(
                        'border-input file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
                        'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
                        'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
                        error && 'border-red-500 focus:border-red-500 focus:ring-red-500',
                        className,
                    )}
                    aria-invalid={!!error}
                    {...props}
                />
                <Button
                    type="button"
                    variant="ghost"
                    size="sm"
                    className="absolute top-0 right-0 h-full px-3 py-2 hover:bg-transparent"
                    onClick={() => setShowPassword(!showPassword)}
                >
                    {showPassword ? <EyeOff className="h-4 w-4 text-gray-500" /> : <Eye className="h-4 w-4 text-gray-500" />}
                </Button>
            </div>

            <InputError message={error} />
        </div>
    );
}
