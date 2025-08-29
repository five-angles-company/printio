import MainLayout from '@/layouts/main-layout';
import React from 'react';

function Printers() {
    return <div>Printers</div>;
}

export default Printers;

Printers.layout = (page: React.ReactNode) => <MainLayout children={page} />;
