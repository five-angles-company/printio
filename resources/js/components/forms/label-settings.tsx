import { useForm } from '@inertiajs/react';
import { Button } from '../ui/button';
import { DialogClose } from '../ui/dialog';
import { Input } from '../ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';

interface LabelSettingsProps {
    settings: Record<string, unknown>;
    printerId: number;
    handleOpen: (open: boolean) => void;
}

function LabelSettings({ settings, printerId, handleOpen }: LabelSettingsProps) {
    const { data, setData, put, processing, errors } = useForm({
        settings: {
            labelWidth: (settings?.labelWidth as string) || '',
            labelHeight: (settings?.labelHeight as string) || '',
            printDensity: (settings?.printDensity as string) || '',
            printSpeed: (settings?.printSpeed as string) || '',
            encoder: (settings?.encoder as string) || '',
        },
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(route('printers.update', printerId), {
            onSuccess: () => handleOpen(false),
        });
    };

    return (
        <div>
            <form onSubmit={handleSubmit}>
                <div className="space-y-6">
                    {/* General Settings */}
                    <div className="space-y-4">
                        <h4 className="font-medium text-slate-900">General Settings</h4>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <label htmlFor="labelWidth">Label width:</label>
                                <Input
                                    id="labelWidth"
                                    value={data.settings.labelWidth}
                                    onChange={(e) => setData('settings', { ...data.settings, labelWidth: e.target.value })}
                                />
                                {errors['settings.labelWidth'] && <p className="text-sm text-red-500">{errors['settings.labelWidth']}</p>}
                            </div>

                            <div className="space-y-2">
                                <label htmlFor="labelHeight">Label height:</label>
                                <Input
                                    id="labelHeight"
                                    value={data.settings.labelHeight}
                                    onChange={(e) => setData('settings', { ...data.settings, labelHeight: e.target.value })}
                                />
                                {errors['settings.labelHeight'] && <p className="text-sm text-red-500">{errors['settings.labelHeight']}</p>}
                            </div>
                        </div>
                    </div>

                    {/* Print Settings */}
                    <div className="space-y-4">
                        <h4 className="font-medium text-slate-900">Print Settings</h4>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <label htmlFor="printDensity">Print Density</label>
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
                                {errors['settings.printDensity'] && <p className="text-sm text-red-500">{errors['settings.printDensity']}</p>}
                            </div>

                            <div className="space-y-2">
                                <label htmlFor="printSpeed">Print Speed</label>
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
                                {errors['settings.printSpeed'] && <p className="text-sm text-red-500">{errors['settings.printSpeed']}</p>}
                            </div>
                        </div>

                        <div className="space-y-2">
                            <label htmlFor="encoder">Encoder</label>
                            <Select
                                value={data.settings.encoder}
                                onValueChange={(value) => setData('settings', { ...data.settings, encoder: value })}
                            >
                                <SelectTrigger className="w-full">
                                    <SelectValue placeholder="Select encoder" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="Tspl">TSPL</SelectItem>
                                    <SelectItem value="Epl">EPL</SelectItem>
                                    <SelectItem value="Zpl">ZPL</SelectItem>
                                </SelectContent>
                            </Select>
                            {errors['settings.encoder'] && <p className="text-sm text-red-500">{errors['settings.encoder']}</p>}
                        </div>
                    </div>
                </div>

                {/* Footer */}
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

export default LabelSettings;
