import { useForm } from '@inertiajs/react';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';

interface ApiSettingsProps {
    apiUrl: string | null;
}

function ApiSettings({ apiUrl }: ApiSettingsProps) {
    const { put, data, setData, processing, errors, reset } = useForm({
        api_url: apiUrl ?? '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(route('settings.update'));
    };

    return (
        <form onSubmit={handleSubmit}>
            <div className="space-y-6">
                <div className="space-y-2">
                    <Label htmlFor="label_printer">Url</Label>
                    <Input
                        type="text"
                        id="api_url"
                        value={data.api_url}
                        onChange={(e) => setData('api_url', e.target.value)}
                        placeholder="http://example.com"
                        required
                    />
                    {errors.api_url && <p className="text-sm text-red-600">{errors.api_url}</p>}
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

export default ApiSettings;
