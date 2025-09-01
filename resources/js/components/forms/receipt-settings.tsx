import { useForm } from '@inertiajs/react';
import { Button } from '../ui/button';
import { DialogClose } from '../ui/dialog';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';
import { Switch } from '../ui/switch';

interface ReceiptSettingsProps {
    settings: Record<string, string | number | boolean>;
    printerId: number;
    handleOpen: (open: boolean) => void;
}

function ReceiptSettings({ settings, printerId, handleOpen }: ReceiptSettingsProps) {
    const { put, data, setData, processing } = useForm({
        settings: {
            paperSize: (settings?.paperSize.toString() as string) || '',
            printDensity: (settings?.printDensity as string) || '',
            printSpeed: (settings?.printSpeed as string) || '',
            cut: (settings?.cut as boolean) || false,
            beep: (settings?.beep as boolean) || false,
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
                <div className="space-y-6">
                    {/* General Settings */}
                    <div className="space-y-4">
                        <h4 className="font-medium text-slate-900">General Settings</h4>
                        <div className="grid grid-cols-2 gap-4">
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
                        </div>
                    </div>

                    {/* Print Settings */}
                    <div className="space-y-4">
                        <h4 className="font-medium text-slate-900">Print Settings</h4>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <label>Print Density</label>
                                <Select
                                    value={data.settings.printDensity}
                                    onValueChange={(value) => setData('settings', { ...data.settings, printDensity: value })}
                                >
                                    <SelectTrigger className="w-full">
                                        <SelectValue placeholder="Select density" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="light">Light</SelectItem>
                                        <SelectItem value="medium_light">Medium Light</SelectItem>
                                        <SelectItem value="medium">Medium</SelectItem>
                                        <SelectItem value="medium_dark">Medium Dark</SelectItem>
                                        <SelectItem value="dark">Dark</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="space-y-2">
                                <label>Print Speed</label>
                                <Select
                                    value={data.settings.printSpeed}
                                    onValueChange={(value) => setData('settings', { ...data.settings, printSpeed: value })}
                                >
                                    <SelectTrigger className="w-full">
                                        <SelectValue placeholder="Select speed" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="slow">Slow</SelectItem>
                                        <SelectItem value="normal">Normal</SelectItem>
                                        <SelectItem value="fast">Fast</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        <div className="flex items-center justify-between">
                            <div className="space-y-0.5">
                                <label className="text-sm font-medium text-slate-700">Auto Cut</label>
                                <p className="text-xs text-slate-500">Automatically cut paper after printing</p>
                            </div>
                            <Switch checked={data.settings.cut} onCheckedChange={(value) => setData('settings', { ...data.settings, cut: value })} />
                        </div>

                        <div className="flex items-center justify-between">
                            <div className="space-y-0.5">
                                <label className="text-sm font-medium text-slate-700">Auto Beep</label>
                                <p className="text-xs text-slate-500">Automatically beep after printing</p>
                            </div>
                            <Switch
                                checked={data.settings.beep}
                                onCheckedChange={(value) => setData('settings', { ...data.settings, beep: value })}
                            />
                        </div>
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
