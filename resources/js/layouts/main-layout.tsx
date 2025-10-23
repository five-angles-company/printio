import FlashToast from '@/components/flash-toast';
import Header from '@/components/header';
import { Toaster } from '@/components/ui/sonner';
import { cn } from '@/lib/utils';
import { Link, usePage } from '@inertiajs/react';
import { Activity, Printer, Settings } from 'lucide-react';

export default function MainLayout({ children }: { children: React.ReactNode }) {
    const { url } = usePage();

    return (
        <div className="min-h-screen bg-slate-50">
            <Header />
            <main className="space-y-6 p-6">
                <nav className="grid grid-cols-3 rounded-md bg-white p-1 shadow-sm">
                    <Link
                        href="/"
                        className={cn(
                            `flex items-center justify-center space-x-2 rounded-md px-3 py-2 text-sm font-medium transition`,
                            url === '/' && 'bg-primary/5 text-primary',
                        )}
                    >
                        <Activity className="h-4 w-4" />
                        <span>Dashboard</span>
                    </Link>
                    <Link
                        href="/printers"
                        className={cn(
                            `flex items-center justify-center space-x-2 rounded-md px-3 py-2 text-sm font-medium transition`,
                            url.startsWith('/printers') && 'bg-primary/5 text-primary',
                        )}
                    >
                        <Printer className="h-4 w-4" />
                        <span>Printers</span>
                    </Link>
                    <Link
                        href="/settings"
                        className={cn(
                            `flex items-center justify-center space-x-2 rounded-md px-3 py-2 text-sm font-medium transition`,
                            url.startsWith('/settings') && 'bg-primary/5 text-primary',
                        )}
                    >
                        <Settings className="h-4 w-4" />
                        <span>Settings</span>
                    </Link>
                </nav>
                {children}
                <FlashToast />
                <Toaster />
            </main>
        </div>
    );
}
