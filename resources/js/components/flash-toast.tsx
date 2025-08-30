// resources/js/Components/FlashToast.tsx
'use client';

import { usePage } from '@inertiajs/react';
import { useEffect } from 'react';
import { toast } from 'sonner';

export default function FlashToast() {
    const { props } = usePage();
    const flash = props.flash as {
        success?: string;
        error?: string;
    };

    useEffect(() => {
        if (flash.success) {
            toast.success(flash.success);
        }
        if (flash.error) {
            toast.error(flash.error);
        }
    }, [flash.success, flash.error]);

    return null;
}
