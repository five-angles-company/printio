import { printerIcon, printerStatusColor, printerStatusIcon } from '@/lib/utils';
import { Printer } from '@/types';
import { router } from '@inertiajs/react';
import { PrinterCheck, XCircle } from 'lucide-react';
import { Button } from './ui/button';
import { Card, CardContent } from './ui/card';

interface PrinterCardProps {
    printer: Printer;
}

function PrinterCard({ printer }: PrinterCardProps) {
    const handleDeletePrinter = () => {
        router.delete(route('printers.destroy', printer.id));
    };
    const handleTestPrinter = () => {};

    return (
        <Card key={printer.id} className="group relative border-0 bg-white shadow-sm">
            <CardContent>
                <div className="mb-4 flex items-start justify-between">
                    <div className="flex items-center space-x-3">
                        <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">{printerIcon(printer.type)}</div>
                        <div>
                            <h3 className="font-semibold text-slate-900">{printer.name}</h3>
                            <p className="text-sm text-slate-500 capitalize">{printer.type} Printer</p>
                        </div>
                    </div>
                    <div
                        className={`flex items-center space-x-1 rounded-full border px-2 py-1 text-xs font-medium ${printerStatusColor(printer.status)}`}
                    >
                        {printerStatusIcon(printer.status)}
                        <span className="capitalize">{printer.status}</span>
                    </div>
                </div>

                <div className="flex items-center justify-end space-x-2">
                    <Button variant="outline" size="sm" onClick={handleTestPrinter}>
                        <PrinterCheck className="mr-1 h-3 w-3" />
                        Test
                    </Button>
                    {/* <PrinterSettings printer={printer} /> */}
                </div>
            </CardContent>

            <XCircle
                className="absolute -top-2 -right-2 hidden h-6 w-6 cursor-pointer rounded-full fill-destructive stroke-white p-0 group-hover:block"
                onClick={() => handleDeletePrinter()}
            />
        </Card>
    );
}

export default PrinterCard;
