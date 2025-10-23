import { cn } from '@/lib/utils';
import { LucideIcon } from 'lucide-react';
import { Card, CardContent } from './ui/card';

interface StatCardProps {
    title: string;
    data: string;
    icon: LucideIcon;
    iconColor?: string;
    iconBg?: string;
}
function StatCard({ title, data, icon: Icon, iconColor, iconBg }: StatCardProps) {
    return (
        <Card className="border-0 bg-white shadow-sm">
            <CardContent>
                <div className="flex items-center justify-between">
                    <div>
                        <p className="text-sm font-medium text-slate-600">{title}</p>
                        <p className="mt-1 text-3xl font-bold text-slate-900">{data}</p>
                    </div>
                    <div className={cn('flex h-12 w-12 items-center justify-center rounded-xl bg-teal-100', iconBg)}>
                        <Icon className={cn('h-6 w-6 text-teal-600', iconColor)} />
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}

export default StatCard;
