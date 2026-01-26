import { useForm } from '@inertiajs/react';
import { Button } from '../ui/button';
import { DialogClose } from '../ui/dialog';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';

interface ReceiptSettingsProps {
    settings: Record<string, string | number | boolean>;
    printerId: number;
    handleOpen: (open: boolean) => void;
}

function ReceiptSettings({ settings, printerId, handleOpen }: ReceiptSettingsProps) {
    const { put, data, setData, processing } = useForm({
        settings: {
            paperSize: (settings?.paperSize?.toString() as string) || '80',
            maxPaperHeight: (settings?.maxPaperHeight?.toString() as string) || '800',
        },
    });

    const handleSubmit = () => {
        put(route('printers.update', printerId), {
            onSuccess: () => handleOpen(false),
        });
    };

    return (
        <div>
            <form
                onSubmit={(e) => {
                    e.preventDefault();
                    handleSubmit();
                }}
            >
                {/* General Settings */}
                <div className="space-y-4">
                    <div className="space-y-2">
                        <label>Paper size:</label>
                        <Select
                            value={data.settings.paperSize}
                            onValueChange={(value) => setData('settings', { ...data.settings, paperSize: value })}
                        >
                            <SelectTrigger className="w-full">
                                <SelectValue placeholder="Select size" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="58">58mm</SelectItem>
                                <SelectItem value="72">72mm</SelectItem>
                                <SelectItem value="80">80mm</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div className="space-y-2">
                        <label>Max paper height:</label>
                        <Select
                            value={data.settings.maxPaperHeight}
                            onValueChange={(value) => setData('settings', { ...data.settings, maxPaperHeight: value })}
                        >
                            <SelectTrigger className="w-full">
                                <SelectValue placeholder="Select max height" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="300">300mm (budget printers)</SelectItem>
                                <SelectItem value="500">500mm</SelectItem>
                                <SelectItem value="800">800mm (default)</SelectItem>
                                <SelectItem value="1000">1000mm</SelectItem>
                                <SelectItem value="2000">2000mm</SelectItem>
                                <SelectItem value="3000">3000mm (high-end printers)</SelectItem>
                            </SelectContent>
                        </Select>
                        <p className="text-xs text-muted-foreground">
                            Long invoices will be split into pages if they exceed this height. Lower this if receipts get cut off.
                        </p>
                    </div>
                </div>

                {/* Buttons */}
                <div className="flex items-center justify-end gap-2 pt-4">
                    <DialogClose asChild>
                        <Button type="button" variant="outline">
                            Cancel
                        </Button>
                    </DialogClose>

                    <Button type="submit" disabled={processing}>
                        {processing ? 'Saving...' : 'Save Settings'}
                    </Button>
                </div>
            </form>
        </div>
    );
}

export default ReceiptSettings;
