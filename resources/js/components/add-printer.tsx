import { SystemPrinter } from '@/types';
import { useForm } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import { useState } from 'react';
import { Button } from './ui/button';
import { Dialog, DialogClose, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from './ui/dialog';
import { Label } from './ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from './ui/select';

interface AddPrinterProps {
    systemPrinters: SystemPrinter[];
}
function AddPrinter({ systemPrinters }: AddPrinterProps) {
    const [open, setOpen] = useState(false);
    const { post, data, setData, processing, reset } = useForm({
        name: '',
        description: '',
        type: '',
    });

    const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        post(route('printers.store'), {
            onSuccess: () => {
                reset();
                setOpen(false);
            },
        });
    };

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button>
                    <Plus className="mr-2 h-4 w-4" />
                    Add Printer
                </Button>
            </DialogTrigger>

            <DialogContent>
                <form onSubmit={handleSubmit} className="grid gap-4">
                    <DialogHeader>
                        <DialogTitle>Add a new printer</DialogTitle>
                    </DialogHeader>

                    <div className="grid gap-3">
                        <Label htmlFor="printer-name">Name</Label>
                        <Select onValueChange={(value) => setData('name', value)} value={data.name}>
                            <SelectTrigger id="printer-name" className="w-full">
                                <SelectValue placeholder="Select printer" />
                            </SelectTrigger>
                            <SelectContent className="w-full">
                                {systemPrinters.map((printer) => (
                                    <SelectItem key={printer.name} value={printer.name}>
                                        {printer.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>

                    <div className="grid gap-3">
                        <Label htmlFor="printer-type">Type</Label>
                        <Select onValueChange={(value) => setData('type', value as 'receipt' | 'a4' | 'label')} value={data.type}>
                            <SelectTrigger id="printer-type" className="w-full">
                                <SelectValue placeholder="Select a type" />
                            </SelectTrigger>
                            <SelectContent className="w-full">
                                <SelectItem value="Label">Label</SelectItem>
                                <SelectItem value="Receipt">Receipt</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <DialogFooter>
                        <DialogClose asChild>
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                        </DialogClose>
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Saving...' : 'Save'}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}

export default AddPrinter;
