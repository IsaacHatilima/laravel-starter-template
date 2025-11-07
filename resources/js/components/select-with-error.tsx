import InputError from '@/components/input-error';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { cn } from '@/lib/utils';
import { usePage } from '@inertiajs/react';

interface SelectWithErrorProps {
    label?: string;
    name: string;
    value: string;
    onChange: (value: string) => void;
    placeholder?: string;
    options: string[];
    className?: string;
}

function SelectWithError({ label, name, value, onChange, placeholder = 'Select an option', options, className }: SelectWithErrorProps) {
    const { errors } = usePage().props as { errors: Record<string, string> };

    const error = errors[name];

    return (
        <div>
            {label && <Label htmlFor={name}>{label}</Label>}

            <Select value={value} onValueChange={onChange}>
                <SelectTrigger
                    className={cn('my-1', error && 'border-red-500 focus:border-red-500 focus:ring-red-500', className)}
                    aria-invalid={!!error}
                    id={name}
                >
                    <SelectValue placeholder={placeholder} />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        {options.map((option) => (
                            <SelectItem key={option} value={option}>
                                {option.charAt(0).toUpperCase() + option.slice(1)}
                            </SelectItem>
                        ))}
                    </SelectGroup>
                </SelectContent>
            </Select>

            <InputError message={error} />
        </div>
    );
}

export default SelectWithError;
