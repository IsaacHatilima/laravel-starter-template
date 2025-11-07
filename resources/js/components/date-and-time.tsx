import { useEffect, useState } from 'react';

function DateAndTime() {
    const [now, setNow] = useState(new Date());

    useEffect(() => {
        const timeout = setTimeout(
            () => {
                setNow(new Date());
                const interval = setInterval(() => {
                    setNow(new Date());
                }, 60 * 1000);
                return () => clearInterval(interval);
            },
            (60 - new Date().getSeconds()) * 1000,
        );

        return () => clearTimeout(timeout);
    }, []);

    const formatted = now.toLocaleString(undefined, {
        dateStyle: 'long',
        timeStyle: 'short',
    });
    return <div className="font-medium text-muted-foreground">{formatted}</div>;
}

export default DateAndTime;
