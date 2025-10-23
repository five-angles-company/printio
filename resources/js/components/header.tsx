import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Avatar, AvatarFallback } from './ui/avatar';
import { Button } from './ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from './ui/dropdown-menu';

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
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Avatar>
                                    <AvatarFallback className="rounded-lg">{initials}</AvatarFallback>
                                </Avatar>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" sideOffset={4} className="w-[200px]">
                                <DropdownMenuLabel className="p-0 font-normal">
                                    <div className="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                        <Avatar>
                                            <AvatarFallback className="rounded-lg">{initials}</AvatarFallback>
                                        </Avatar>
                                        <div className="grid flex-1 text-left text-sm leading-tight">
                                            <span className="truncate font-medium">{user.name}</span>
                                            <span className="truncate text-xs">{user.email}</span>
                                        </div>
                                    </div>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <DropdownMenuGroup>
                                    <DropdownMenuItem>
                                        <Link href={route('auth.logout')} method="post">
                                            Logout
                                        </Link>
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    )}
                </div>
            </div>
        </header>
    );
}

export default Header;
