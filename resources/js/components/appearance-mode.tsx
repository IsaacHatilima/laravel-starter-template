import { Button } from '@/components/ui/button';
import { Appearance, useAppearance } from '@/hooks/use-appearance';
import { LucideIcon, Monitor, Moon, Sun } from 'lucide-react';

function AppearanceMode() {
    const { appearance, updateAppearance } = useAppearance();

    const options: { value: Appearance; icon: LucideIcon; label: string }[] = [
        { value: 'light', icon: Sun, label: 'Light' },
        { value: 'dark', icon: Moon, label: 'Dark' },
        { value: 'system', icon: Monitor, label: 'System' },
    ];

    const cycleAppearance = () => {
        const currentIndex = options.findIndex(
            (opt) => opt.value === appearance,
        );
        const nextIndex = (currentIndex + 1) % options.length;
        const nextValue = options[nextIndex].value;
        updateAppearance(nextValue);
    };

    const CurrentIcon =
        options.find((opt) => opt.value === appearance)?.icon || Sun;

    return (
        <Button
            onClick={cycleAppearance}
            variant="ghost"
            size="icon"
            className="h-10 w-10 rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-accent hover:dark:bg-neutral-700"
        >
            <CurrentIcon className="h-4 w-4" />
        </Button>
    );
}

export default AppearanceMode;
