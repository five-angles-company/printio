import type { Config } from 'ziggy-js';

// --------------------
// Auth & Shared Data
// --------------------
export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string | Date;
    updated_at: string | Date;
    [key: string]: unknown;
}

export interface Auth {
    user: User;
}

export type SharedData = {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
} & Record<string, unknown>;

// --------------------
// Printers & Jobs
// --------------------
export type PrinterStatus = 'Ready' | 'Printer Busy' | 'Printer Door Open' | 'Paper Out' | 'Printer Offline' | 'Error' | 'Printing';

export type PrinterType = 'Label' | 'Receipt';

export type PrintJobStatus = 'Pending' | 'Completed' | 'Failed';

export interface PrinterSettings {
    id: number;
    settings: Record<string, string | number | boolean>;
    printer_id: number;
    printer?: Printer;
}

export interface SystemPrinter {
    name: string;
    displayName: string;
    description?: string;
}

export interface Printer {
    id: number;
    name: string;
    display_name: string;
    description?: string;
    type: PrinterType;
    status: PrinterStatus;
    printer_settings?: PrinterSettings;
    created_at: string | Date;
    updated_at: string | Date;
}

export interface PrintJob {
    id: number;
    name: string;
    type: PrinterType;
    status: PrintJobStatus;
    printer_id: number;
    printer?: Printer;
    created_at: string | Date;
    updated_at: string | Date;
}

export interface UpdateInfo {
    releaseName: string;
    version: string;
    releaseDate: string;
}

export interface DownloadProgress {
    total: number;
    delta: number;
    transferred: number;
    percent: number;
    bytesPerSecond: number;
}

export interface UpdateDownloaded {
    version: string;
    downloadedFile: string;
    releaseDate: string;
    releaseNotes: string | string[] | null;
    releaseName: string | null;
}
