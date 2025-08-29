import { PrinterStatus, PrinterType } from '@/types';
import { clsx, type ClassValue } from 'clsx';
import { AlertTriangle, Barcode, CheckCircle2, Clock, DoorOpen, FileX2, Loader2, Printer, Receipt, WifiOff, XCircle } from 'lucide-react';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function statusColor(status: string) {
    switch (status) {
        case 'completed':
            return 'bg-emerald-100 text-emerald-700 border-emerald-200';
        case 'failed':
            return 'bg-red-100 text-red-700 border-red-200';
        case 'pending':
            return 'bg-amber-100 text-amber-700 border-amber-200';
        default:
            return 'bg-gray-100 text-gray-700 border-gray-200';
    }
}
export function statusIcon(status: string) {
    switch (status) {
        case 'completed':
            return <CheckCircle2 className="h-3 w-3" />;
        case 'failed':
            return <XCircle className="h-3 w-3" />;
        case 'pending':
            return <Clock className="h-3 w-3" />;
        default:
            return <Clock className="h-3 w-3" />;
    }
}

export function printerIcon(type: PrinterType) {
    switch (type) {
        case 'Receipt':
            return <Receipt className="h-4 w-4" />;
        case 'Label':
            return <Barcode className="h-4 w-4" />;
        default:
            return <Printer className="h-4 w-4" />;
    }
}

export function printerStatusIcon(status: PrinterStatus) {
    switch (status) {
        case 'Ready':
            return <CheckCircle2 className="h-4 w-4 text-green-600" />;
        case 'Printing':
            return <Loader2 className="h-4 w-4 animate-spin text-blue-600" />;
        case 'Printer Busy':
            return <Clock className="h-4 w-4 text-yellow-600" />;
        case 'Printer Door Open':
            return <DoorOpen className="h-4 w-4 text-orange-600" />;
        case 'Paper Out':
            return <FileX2 className="h-4 w-4 text-red-500" />;
        case 'Printer Offline':
            return <WifiOff className="h-4 w-4 text-gray-500" />;
        case 'Error':
            return <XCircle className="h-4 w-4 text-red-600" />;
        default:
            return <AlertTriangle className="h-4 w-4 text-gray-400" />;
    }
}

export function printerStatusColor(status: PrinterStatus): string {
    switch (status) {
        case 'Ready':
            return 'text-green-600';
        case 'Printing':
            return 'text-blue-600';
        case 'Printer Busy':
            return 'text-yellow-600';
        case 'Printer Door Open':
            return 'text-orange-600';
        case 'Paper Out':
            return 'text-red-500';
        case 'Printer Offline':
            return 'text-gray-500';
        case 'Error':
            return 'text-red-600';
        default:
            return 'text-gray-400';
    }
}
