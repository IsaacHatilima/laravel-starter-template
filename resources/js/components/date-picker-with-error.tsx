import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Label } from '@/components/ui/label';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { usePage } from '@inertiajs/react';
import { format } from 'date-fns';
import { ChevronDownIcon } from 'lucide-react';
import * as React from 'react';

interface DatePickerProps {
    value?: string;
    onChange: (date: string) => void;
    label?: string;
    name: string;
    placeholder?: string;
    className?: string;
}

function DatePickerWithError({ value, onChange, label, name, placeholder = 'Select date', className }: DatePickerProps) {
    const { errors } = usePage().props as { errors: Record<string, string> };
    const [open, setOpen] = React.useState(false);
    const error = errors[name];

    const selectedDate = value ? new Date(value) : undefined;

    return (
        <div className="grid gap-2">
            {label && <Label htmlFor={name}>{label}</Label>}

            <Popover open={open} onOpenChange={setOpen}>
                <PopoverTrigger asChild>
                    <Button
                        variant="outline"
                        id={name}
                        className={`justify-between font-normal ${error ? 'border-red-500' : ''} ${className}`}
                        aria-invalid={!!error}
                    >
                        {value ? value : placeholder}
                        <ChevronDownIcon />
                    </Button>
                </PopoverTrigger>
                <PopoverContent className="w-auto overflow-hidden p-0" align="start">
                    <Calendar
                        mode="single"
                        selected={selectedDate}
                        captionLayout="dropdown"
                        onSelect={(date) => {
                            if (date) {
                                onChange(format(date, 'yyyy-MM-dd'));
                                setOpen(false);
                            }
                        }}
                    />
                </PopoverContent>
            </Popover>

            <InputError message={error} />
        </div>
    );
}

export default DatePickerWithError;
