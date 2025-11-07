import InputError from '@/components/input-error';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { usePage } from '@inertiajs/react';
import React from 'react';

interface InputWithErrorProps extends React.ComponentProps<typeof Input> {
    label?: string;
    name: string;
}

export default function InputWithError({ label, name, className, ...props }: InputWithErrorProps) {
    const { errors } = usePage().props as { errors: Record<string, string> };

    const error = errors[name];

    return (
        <div>
            {label && <Label htmlFor={name}>{label}</Label>}

            <Input
                id={name}
                name={name}
                className={cn('my-1', error && 'border-red-500 focus:border-red-500 focus:ring-red-500', className)}
                aria-invalid={!!error}
                {...props}
            />

            <InputError message={error} />
        </div>
    );
}
