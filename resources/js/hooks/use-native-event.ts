/* eslint-disable @typescript-eslint/no-explicit-any */
import { useEffect } from 'react';

export function useNativeEvent<T = unknown>(eventName: string, handler: (payload: T, event?: any) => void) {
    useEffect(() => {
        if (window.Native && typeof window.Native.on === 'function') {
            window.Native.on(eventName, handler as any);
        }
    }, [eventName, handler]);
}
