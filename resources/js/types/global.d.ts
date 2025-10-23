/* eslint-disable @typescript-eslint/no-explicit-any */
import type { route as routeFn } from 'ziggy-js';

declare global {
    const route: typeof routeFn;
    interface Native {
        on: (event: string, callback: (payload: any, event?: any) => void) => void;
    }

    interface Window {
        Native: Native;
    }
}
