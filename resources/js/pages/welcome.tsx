import { Button } from '@/components/ui/button';
import MainLayout from '@/layouts/main-layout';

export default function Welcome() {
    return (
        <>
            <div className="p-4">
                <Button>Test</Button>
            </div>
        </>
    );
}

Welcome.layout = (page: React.ReactNode) => <MainLayout children={page} />;
