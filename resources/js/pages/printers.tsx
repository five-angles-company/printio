import AddPrinter from '@/components/add-printer';
import PrinterCard from '@/components/printer-card';
import MainLayout from '@/layouts/main-layout';
import { Printer, SystemPrinter } from '@/types';
import { ListX } from 'lucide-react';
import React from 'react';

interface PrintersProps {
    printers: Printer[];
    systemPrinters: SystemPrinter[];
}
function Printers({ printers, systemPrinters }: PrintersProps) {
    return (
        <div>
            <div className="flex items-center justify-between">
                <div>
                    <h2 className="text-2xl font-bold text-slate-900">Printers</h2>
                    <p className="text-slate-600">Manage your printing devices</p>
                </div>
                <AddPrinter systemPrinters={systemPrinters} />
            </div>

            {printers.length === 0 ? (
                <div className="mt-20 flex flex-col items-center justify-center text-center text-slate-500">
                    <ListX className="mb-4 h-16 w-16 text-slate-400" />
                    <h3 className="text-xl font-semibold text-slate-700">No Printers Found</h3>
                    <p className="mt-1 text-sm">You haven’t added any printers yet.</p>
                </div>
            ) : (
                <div className="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {printers.map((printer) => (
                        <PrinterCard key={printer.id} printer={printer} />
                    ))}
                </div>
            )}
        </div>
    );
}

export default Printers;

Printers.layout = (page: React.ReactNode) => <MainLayout children={page} />;
