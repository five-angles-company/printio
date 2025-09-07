import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { LogOut } from 'lucide-react';
import { Button } from './ui/button';

function Header() {
    const {
        auth: { user, token },
    } = usePage<SharedData>().props;

    const initials = user?.name
        ? user.name
              .split(' ')
              .map((n: string) => n[0])
              .join('')
              .toUpperCase()
        : '?';

    return (
        <header className="border-b border-slate-200 bg-white px-6 py-4 shadow-sm">
            <div className="flex items-center justify-between">
                {/* App Branding */}
                <div className="flex items-center space-x-3">
                    <div>
                        <h1 className="text-xl font-semibold text-slate-900">Printer Bridge</h1>
                        <p className="text-sm text-slate-500">Pharmacy Print Management</p>
                    </div>
                </div>

                {/* Right side */}
                <div className="flex items-center space-x-3">
                    {!token ? (
                        <Link href={route('auth.login')}>
                            <Button variant="outline">Login</Button>
                        </Link>
                    ) : (
                        <>
                            {/* Avatar with tooltip */}
                            <TooltipProvider>
                                <Tooltip>
                                    <TooltipTrigger asChild>
                                        <Avatar className="h-9 w-9 cursor-pointer ring-2 ring-slate-200">
                                            <AvatarFallback>{initials}</AvatarFallback>
                                        </Avatar>
                                    </TooltipTrigger>
                                    <TooltipContent side="bottom" className="text-sm">
                                        <p className="font-medium">{user?.name}</p>
                                    </TooltipContent>
                                </Tooltip>
                            </TooltipProvider>

                            {/* Rounded Logout button */}
                            <Link method="post" href={route('auth.logout')}>
                                <Button size="icon" variant="ghost" className="rounded-full text-red-600 hover:bg-red-50">
                                    <LogOut className="h-5 w-5" />
                                </Button>
                            </Link>
                        </>
                    )}
                </div>
            </div>
        </header>
    );
}

export default Header;
