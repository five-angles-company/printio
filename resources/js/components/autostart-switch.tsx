'use client';

import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { SharedData } from '@/types';
import { router, usePage } from '@inertiajs/react';
import { Power } from 'lucide-react';
import { useEffect, useId, useState } from 'react';

export default function AutoStartSwitch() {
    const id = useId();
    const { autoStart } = usePage<SharedData>().props;

    const [checked, setChecked] = useState(autoStart);
    const [isLoading, setIsLoading] = useState(false);

    // React to server-side changes (e.g. from another tab or reload)
    useEffect(() => {
        setChecked(autoStart);
    }, [autoStart]);

    const handleChange = (next: boolean) => {
        // Optimistic update
        const prev = checked;
        setChecked(next);
        setIsLoading(true);

        router.put(
            route('settings.update'),
            { auto_start: next },
            {
                preserveScroll: true,
                onSuccess: () => {
                    setIsLoading(false);
                },
                onError: (errors) => {
                    console.error(errors);
                    // revert to previous value on failure
                    setChecked(prev);
                    setIsLoading(false);
                },
                onFinish: () => setIsLoading(false),
            },
        );
    };

    return (
        <div className="flex items-center gap-3">
            <div className="relative inline-grid h-9 grid-cols-[1fr_1fr] items-center text-sm font-medium">
                <Switch
                    id={id}
                    checked={checked}
                    onCheckedChange={handleChange}
                    disabled={isLoading}
                    className="peer absolute inset-0 h-[inherit] w-auto rounded-md transition-colors disabled:opacity-70 data-[state=checked]:bg-primary data-[state=unchecked]:bg-input/50 [&_span]:z-10 [&_span]:h-full [&_span]:w-1/2 [&_span]:rounded-sm [&_span]:transition-transform [&_span]:duration-300 [&_span]:ease-[cubic-bezier(0.16,1,0.3,1)] [&_span]:data-[state=checked]:translate-x-full [&_span]:data-[state=checked]:rtl:-translate-x-full"
                />

                {/* Off state */}
                <span className="pointer-events-none relative ms-0.5 flex items-center justify-center px-2 text-center transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] peer-data-[state=checked]:invisible peer-data-[state=unchecked]:translate-x-full peer-data-[state=unchecked]:rtl:-translate-x-full">
                    <Power className="h-4 w-4 text-muted-foreground" />
                </span>

                {/* On state */}
                <span className="pointer-events-none relative me-0.5 flex items-center justify-center px-2 text-center transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] peer-data-[state=checked]:-translate-x-full peer-data-[state=checked]:text-background peer-data-[state=unchecked]:invisible peer-data-[state=checked]:rtl:translate-x-full">
                    <Power className="h-4 w-4" />
                </span>
            </div>

            <Label htmlFor={id} className="sr-only">
                Auto Start
            </Label>
        </div>
    );
}
