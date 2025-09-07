import { Printer } from '@/types';
import { useForm } from '@inertiajs/react';
import { Button } from '../ui/button';
import { Label } from '../ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';

interface ConnectionSettingsProps {
    labelPrinter: number | null;
    receiptPrinter: number | null;
    printers: Printer[];
}

function PrintersSettings({ labelPrinter, receiptPrinter, printers }: ConnectionSettingsProps) {
    const { put, data, setData, processing, errors, reset } = useForm({
        label_printer: labelPrinter ?? '',
        receipt_printer: receiptPrinter ?? '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(route('settings.update'));
    };

    return (
        <form onSubmit={handleSubmit}>
            <div className="space-y-6">
                {/* Label Printer */}
                <div className="space-y-2">
                    <Label htmlFor="label_printer">Label Printer</Label>
                    <Select
                        onValueChange={(value) => setData('label_printer', Number(value))}
                        value={data.label_printer ? String(data.label_printer) : ''}
                        disabled={!printers.length}
                    >
                        <SelectTrigger id="label_printer" className="w-full">
                            <SelectValue placeholder="Select printer" />
                        </SelectTrigger>
                        <SelectContent className="w-full">
                            {printers?.map((printer) => (
                                <SelectItem key={printer.id} value={String(printer.id)}>
                                    {printer.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {errors.label_printer && <p className="text-sm text-red-600">{errors.label_printer}</p>}
                </div>

                {/* Receipt Printer */}
                <div className="space-y-2">
                    <Label htmlFor="receipt_printer">Receipt Printer</Label>
                    <Select
                        onValueChange={(value) => setData('receipt_printer', Number(value))}
                        value={data.receipt_printer ? String(data.receipt_printer) : ''}
                        disabled={!printers.length}
                    >
                        <SelectTrigger id="receipt_printer" className="w-full">
                            <SelectValue placeholder="Select printer" />
                        </SelectTrigger>
                        <SelectContent className="w-full">
                            {printers?.map((printer) => (
                                <SelectItem key={printer.id} value={String(printer.id)}>
                                    {printer.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {errors.receipt_printer && <p className="text-sm text-red-600">{errors.receipt_printer}</p>}
                </div>
            </div>

            <div className="flex items-center justify-end gap-2 pt-4">
                <Button variant={'outline'} type="button" disabled={processing} onClick={() => reset()}>
                    Reset
                </Button>
                <Button type="submit" disabled={processing}>
                    Save Changes
                </Button>
            </div>
        </form>
    );
}

export default PrintersSettings;
