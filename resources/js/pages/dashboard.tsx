import StatCard from '@/components/stat-card';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import MainLayout from '@/layouts/main-layout';
import { printerIcon, statusColor, statusIcon } from '@/lib/utils';
import { PrintJob } from '@/types';
import { formatDistanceToNow } from 'date-fns';
import { CheckCircle2, FileText, Printer } from 'lucide-react';

interface DashboardProps {
    stats: {
        totalPrinters: number;
        totalJobs: number;
        successRate: number;
    };
    jobs: PrintJob[];
}
export default function Dashboard({ jobs, stats }: DashboardProps) {
    return (
        <div className="space-y-4 p-2">
            <div className="grid grid-cols-3 gap-6">
                <StatCard title="Printers" data={stats.totalPrinters.toString()} icon={Printer} />
                <StatCard title="Total Print Jobs" data={stats.totalJobs.toString()} icon={FileText} iconColor="text-blue-600" iconBg="bg-blue-100" />
                <StatCard
                    title="Success Rate"
                    data={stats.successRate.toFixed(2) + '%'}
                    icon={CheckCircle2}
                    iconColor="text-emerald-600"
                    iconBg="bg-emerald-100"
                />
            </div>
            <div>
                <Card className="border-0 bg-white shadow-sm">
                    <CardHeader className="pb-4">
                        <CardTitle className="text-lg font-semibold text-slate-900">Recent Activity</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        {jobs.map((job) => (
                            <div key={job.id} className="flex items-center justify-between rounded-lg bg-slate-50 p-3">
                                <div className="flex items-center space-x-3">
                                    <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm">
                                        {printerIcon(job.type)}
                                    </div>
                                    <div>
                                        <p className="font-medium text-slate-900">{job.name}</p>
                                        <p className="text-sm text-slate-500">
                                            {job.printer?.name} • {formatDistanceToNow(job.created_at, { addSuffix: true })}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    className={`flex items-center space-x-1 rounded-full border px-2 py-1 text-xs font-medium ${statusColor(job.status)}`}
                                >
                                    {statusIcon(job.status)}
                                    <span className="capitalize">{job.status}</span>
                                </div>
                            </div>
                        ))}
                    </CardContent>
                </Card>
            </div>
        </div>
    );
}

Dashboard.layout = (page: React.ReactNode) => <MainLayout children={page} />;
