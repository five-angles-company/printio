import { useForm } from '@inertiajs/react';
import { Button } from '../ui/button';
import { DialogClose } from '../ui/dialog';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';

interface LabelSettingsProps {
    settings: Record<string, unknown>;
    printerId: number;
    handleOpen: (open: boolean) => void;
}

function LabelSettings({ settings, printerId, handleOpen }: LabelSettingsProps) {
    const { data, setData, put, processing, errors } = useForm({
        settings: {
            labelSize: (settings?.labelSize as string) || '',
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
                        <div className="space-y-2">
                            <label htmlFor="labelSize">Label Size</label>
                            <Select
                                value={data.settings.labelSize}
                                onValueChange={(value) => setData('settings', { ...data.settings, labelSize: value })}
                            >
                                <SelectTrigger className="w-full">
                                    <SelectValue placeholder="Select Size" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="4x4">1" x 4"</SelectItem>
                                    <SelectItem value="4x3">4" x 3"</SelectItem>
                                    <SelectItem value="4x2">4" x 2"</SelectItem>
                                    <SelectItem value="4x1">4" x 1"</SelectItem>
                                    <SelectItem value="3x3">3" x 3"</SelectItem>
                                    <SelectItem value="3x2">3" x 2"</SelectItem>
                                    <SelectItem value="3x1">3" x 1"</SelectItem>
                                    <SelectItem value="2x2">2" x 2"</SelectItem>
                                    <SelectItem value="2x1">2" x 1"</SelectItem>
                                    <SelectItem value="1x1">1" x 1</SelectItem>
                                </SelectContent>
                            </Select>
                            {errors['settings.labelSize'] && <p className="text-sm text-red-500">{errors['settings.labelSize']}</p>}
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
