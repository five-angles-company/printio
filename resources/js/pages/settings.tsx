import AppUpdater from '@/components/app-updater';
import ApiSettings from '@/components/forms/api-settings';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import MainLayout from '@/layouts/main-layout';

interface SettingsProps {
    apiUrl: string | null;
    deviceId: string | null;
}
function Settings({ apiUrl, deviceId }: SettingsProps) {
    return (
        <div>
            <div>
                <h2 className="text-2xl font-bold text-slate-900">Settings</h2>
                <p className="text-slate-600">Configure your printer bridge</p>
            </div>
            <div className="mt-4 space-y-4">
                <AppUpdater />
                <Card>
                    <CardHeader>
                        <CardTitle>Api</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <ApiSettings apiUrl={apiUrl} deviceId={deviceId} />
                    </CardContent>
                </Card>
            </div>
        </div>
    );
}

export default Settings;

Settings.layout = (page: React.ReactNode) => <MainLayout children={page} />;
