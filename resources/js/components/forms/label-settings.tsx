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
            fontSize: (settings?.fontSize as string) || '',
            barcodeSize: (settings?.barcodeSize as string) || '',
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
                        <div className="flex items-center gap-4">
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
                        <div className="flex items-center gap-4">
                            <div className="w-full space-y-2">
                                <label htmlFor="fontSize">Font Size</label>
                                <Select
                                    value={data.settings.fontSize}
                                    onValueChange={(value) => setData('settings', { ...data.settings, fontSize: value })}
                                >
                                    <SelectTrigger className="w-full">
                                        <SelectValue placeholder="Select Font Size" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="xs">Extra Small</SelectItem>
                                        <SelectItem value="s">Small</SelectItem>
                                        <SelectItem value="m">Normal</SelectItem>
                                        <SelectItem value="l">Large</SelectItem>
                                        <SelectItem value="xl">Extra Large</SelectItem>
                                    </SelectContent>
                                </Select>
                                {errors['settings.fontSize'] && <p className="text-sm text-red-500">{errors['settings.fontSize']}</p>}
                            </div>
                            <div className="w-full space-y-2">
                                <label htmlFor="barcodeSize">Barcode Size</label>
                                <Select
                                    value={data.settings.barcodeSize}
                                    onValueChange={(value) => setData('settings', { ...data.settings, barcodeSize: value })}
                                >
                                    <SelectTrigger className="w-full">
                                        <SelectValue placeholder="Select Barcode Size" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="xs">Extra Small</SelectItem>
                                        <SelectItem value="s">Small</SelectItem>
                                        <SelectItem value="m">Normal</SelectItem>
                                        <SelectItem value="l">Large</SelectItem>
                                        <SelectItem value="xl">Extra Large</SelectItem>
                                    </SelectContent>
                                </Select>
                                {errors['settings.barcodeSize'] && <p className="text-sm text-red-500">{errors['settings.barcodeSize']}</p>}
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
