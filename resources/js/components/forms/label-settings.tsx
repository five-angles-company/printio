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
            label_width: (settings?.label_width as string) || '',
            label_height: (settings?.label_height as string) || '',
            print_density: (settings?.print_density as string) || '',
            print_speed: (settings?.print_speed as string) || '',
            encoder: (settings?.encoder as string) || '',
        },
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(route('printers.update-settings', printerId), {
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
                                <label htmlFor="label_width">Label width:</label>
                                <Input
                                    id="label_width"
                                    value={data.settings.label_width}
                                    onChange={(e) => setData('settings', { ...data.settings, label_width: e.target.value })}
                                />
                                {errors['settings.label_width'] && <p className="text-sm text-red-500">{errors['settings.label_width']}</p>}
                            </div>

                            <div className="space-y-2">
                                <label htmlFor="label_height">Label height:</label>
                                <Input
                                    id="label_height"
                                    value={data.settings.label_height}
                                    onChange={(e) => setData('settings', { ...data.settings, label_height: e.target.value })}
                                />
                                {errors['settings.label_height'] && <p className="text-sm text-red-500">{errors['settings.label_height']}</p>}
                            </div>
                        </div>
                    </div>

                    {/* Print Settings */}
                    <div className="space-y-4">
                        <h4 className="font-medium text-slate-900">Print Settings</h4>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <label htmlFor="print_density">Print Density</label>
                                <Select
                                    value={data.settings.print_density}
                                    onValueChange={(value) => setData('settings', { ...data.settings, print_density: value })}
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
                                {errors['settings.print_density'] && <p className="text-sm text-red-500">{errors['settings.print_density']}</p>}
                            </div>

                            <div className="space-y-2">
                                <label htmlFor="print_speed">Print Speed</label>
                                <Select
                                    value={data.settings.print_speed}
                                    onValueChange={(value) => setData('settings', { ...data.settings, print_speed: value })}
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
                                {errors['settings.print_speed'] && <p className="text-sm text-red-500">{errors['settings.print_speed']}</p>}
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
                                    <SelectItem value="tspl">TSPL</SelectItem>
                                    <SelectItem value="epl">EPL</SelectItem>
                                    <SelectItem value="zpl">ZPL</SelectItem>
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
