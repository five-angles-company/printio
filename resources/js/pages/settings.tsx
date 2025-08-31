import PrintersSettings from '@/components/forms/printers-settings';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import MainLayout from '@/layouts/main-layout';
import { Printer } from '@/types';

interface SettingsProps {
    labelPrinter: Printer | null;
    receiptPrinter: Printer | null;
    printers: Printer[];
}
function Settings({ labelPrinter, receiptPrinter, printers }: SettingsProps) {
    return (
        <div>
            <div>
                <h2 className="text-2xl font-bold text-slate-900">Settings</h2>
                <p className="text-slate-600">Configure your printer bridge</p>
            </div>
            <div className="mt-4 space-y-4">
                <Card>
                    <CardHeader>
                        <CardTitle>Printers</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <PrintersSettings labelPrinter={labelPrinter} receiptPrinter={receiptPrinter} printers={printers} />
                    </CardContent>
                </Card>
            </div>
        </div>
    );
}

export default Settings;

Settings.layout = (page: React.ReactNode) => <MainLayout children={page} />;
