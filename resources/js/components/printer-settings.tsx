import { Printer } from '@/types';
import { Settings } from 'lucide-react';
import { useState } from 'react';
import LabelSettings from './forms/label-settings';
import ReceiptSettings from './forms/receipt-settings';
import { Button } from './ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from './ui/dialog';

interface PrinterSettingsProps {
    printer: Printer;
}
function PrinterSettings({ printer }: PrinterSettingsProps) {
    const [open, setOpen] = useState(false);
    console.log(printer);
    const renderSettingsForm = () => {
        switch (printer.type) {
            case 'Receipt':
                return (
                    <ReceiptSettings
                        settings={printer.printer_settings?.settings || {}}
                        printerId={printer.id}
                        handleOpen={(open) => setOpen(open)}
                    />
                );
            case 'Label':
                return <LabelSettings settings={printer.printer_settings?.settings || {}} printerId={printer.id} handleOpen={setOpen} />;
            default:
                return <div>Unknown printer type</div>;
        }
    };
    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button variant="outline" size="sm">
                    <Settings className="mr-1 h-3 w-3" />
                    Settings
                </Button>
            </DialogTrigger>
            <DialogContent className="min-w-[600px]">
                <DialogHeader>
                    <DialogTitle className="flex items-center space-x-2">
                        <span>{printer.name} Settings</span>
                    </DialogTitle>
                </DialogHeader>

                {renderSettingsForm()}
            </DialogContent>
        </Dialog>
    );
}

export default PrinterSettings;
