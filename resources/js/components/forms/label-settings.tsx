import { useForm } from '@inertiajs/react';
import { Button } from '../ui/button';
import { DialogClose } from '../ui/dialog';
import { Input } from '../ui/input';

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
                        <div className="w-full space-y-2">
                            <label htmlFor="labelWidth">Label Width</label>
                            <Input
                                type="text"
                                id="labelWidth"
                                value={data.settings.labelWidth}
                                onChange={(e) => setData('settings', { ...data.settings, labelWidth: e.target.value })}
                                required
                            />
                            {errors['settings.labelWidth'] && <p className="text-sm text-red-500">{errors['settings.labelWidth']}</p>}
                        </div>
                        <div className="w-full space-y-2">
                            <label htmlFor="labelHeight">Label Height</label>
                            <Input
                                type="text"
                                id="labelHeight"
                                value={data.settings.labelHeight}
                                onChange={(e) => setData('settings', { ...data.settings, labelHeight: e.target.value })}
                                required
                            />
                            {errors['settings.labelHeight'] && <p className="text-sm text-red-500">{errors['settings.labelHeight']}</p>}
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
