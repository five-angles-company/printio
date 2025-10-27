import AppUpdater from '@/components/app-updater';
import ApiSettings from '@/components/forms/api-settings';
import PrintersSettings from '@/components/forms/printers-settings';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import MainLayout from '@/layouts/main-layout';
import { Printer } from '@/types';

interface SettingsProps {
    labelPrinter: number | null;
    receiptPrinter: number | null;
    instructionsPrinter: number | null;
    posSessionPrinter: number | null;
    apiUrl: string | null;
    deviceId: string | null;
    printers: Printer[];
}
function Settings({ labelPrinter, receiptPrinter, instructionsPrinter, posSessionPrinter, apiUrl, deviceId, printers }: SettingsProps) {
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
                        <CardTitle>Printers</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <PrintersSettings
                            labelPrinter={labelPrinter}
                            receiptPrinter={receiptPrinter}
                            instructionsPrinter={instructionsPrinter}
                            posSessionPrinter={posSessionPrinter}
                            printers={printers}
                        />
                    </CardContent>
                </Card>
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
